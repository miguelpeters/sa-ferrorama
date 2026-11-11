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

require_once __DIR__ . '/../infra/conexao.php';

$nome    = trim($_POST['nome'] ?? '');
$tipo    = trim($_POST['tipo'] ?? '');
$rota_id = filter_input(INPUT_POST, 'rota_id', FILTER_VALIDATE_INT);

if ($nome === '' || $tipo === '' || !$rota_id) {
    $erro = 'Preencha todos os campos e selecione uma rota.';
} else {
    $verifica = $conexao->prepare("SELECT id FROM rotas WHERE id = ?");
    $verifica->bind_param('i', $rota_id);
    $verifica->execute();
    $rotaExiste = $verifica->get_result()->num_rows > 0;
    $verifica->close();

    if (!$rotaExiste) {
        $erro = 'A rota selecionada não existe.';
    } else {
        $stmt = $conexao->prepare(
            "INSERT INTO sensores (nome, tipo, rota_id) VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            $erro = 'Erro ao preparar cadastro: ' . $conexao->error;
        } else {
            $stmt->bind_param('ssi', $nome, $tipo, $rota_id);

            if ($stmt->execute()) {
                echo json_encode([
                    'ok' => true,
                    'id' => $conexao->insert_id,
                    'mensagem' => 'Sensor cadastrado com sucesso! ID: ' . $conexao->insert_id
                ]);
                $stmt->close();
                exit;
            }

            $erro = 'Erro ao cadastrar sensor: ' . $stmt->error;
            $stmt->close();
        }
    }
}

http_response_code(400);
echo json_encode(['ok' => false, 'mensagem' => $erro]);