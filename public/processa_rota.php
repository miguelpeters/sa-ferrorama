<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$tipoUsuario = $_SESSION['tipo'] ?? '';

if (!in_array($tipoUsuario, ['funcionario', 'gerente'], true)) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensagem' => 'Sessão expirada. Faça login novamente.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

include '../infra/conexao.php';

$nome         = trim($_POST['nome'] ?? '');
$descricao    = trim($_POST['descricao'] ?? '');
$distancia    = $_POST['distancia'] ?? '';
$duracao      = $_POST['duracao'] ?? '';
$data_criacao = $_POST['data_criacao'] ?? '';
$trem_id      = $_POST['trem_id'] ?? '';

if (
    $nome === '' || $descricao === '' || $distancia === '' ||
    $duracao === '' || $data_criacao === '' || $trem_id === ''
) {
    $erro = 'Preencha todos os campos.';
} elseif (
    !is_numeric($distancia) || (float) $distancia <= 0 ||
    !is_numeric($duracao) || (float) $duracao <= 0
) {
    $erro = 'Informe uma distância e duração válidas.';
} elseif (!ctype_digit((string) $trem_id) || (int) $trem_id < 1) {
    $erro = 'Selecione um trem válido.';
} else {
    $stmt = $conexao->prepare(
        "INSERT INTO rotas (nome, descricao, distancia, duracao, data_criacao, trem_id)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        $erro = 'Erro ao preparar cadastro: ' . $conexao->error;
    } else {
        $distancia = (float) $distancia;
        $duracao   = (float) $duracao;
        $trem_id   = (int) $trem_id;

        $stmt->bind_param('ssddsi', $nome, $descricao, $distancia, $duracao, $data_criacao, $trem_id);

        if ($stmt->execute()) {
            echo json_encode([
                'ok' => true,
                'mensagem' => 'Rota cadastrada com sucesso! ID: ' . $conexao->insert_id
            ]);
            $stmt->close();
            exit;
        }

        $erro = 'Erro ao cadastrar rota: ' . $stmt->error;
        $stmt->close();
    }
}

http_response_code(400);
echo json_encode(['ok' => false, 'mensagem' => $erro]);