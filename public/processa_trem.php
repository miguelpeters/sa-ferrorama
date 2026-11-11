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

$modelo         = trim($_POST['modelo'] ?? '');
$serie          = trim($_POST['serie'] ?? '');
$estado         = $_POST['estado'] ?? '';
$funcionario_id = $_POST['funcionario_id'] ?? '';

if ($modelo === '' || $serie === '' || $estado === '' || $funcionario_id === '') {
    $erro = 'Preencha todos os campos.';
} elseif (!in_array($estado, ['operante', 'inativo'], true)) {
    $erro = 'Estado inválido.';
} elseif (!ctype_digit((string) $funcionario_id) || (int) $funcionario_id < 1) {
    $erro = 'Selecione um funcionário válido.';
} else {
    $stmt = $conexao->prepare(
        "INSERT INTO trens (modelo, serie, estado, funcionario_id) VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        $erro = 'Erro ao preparar cadastro: ' . $conexao->error;
    } else {
        $funcionario_id = (int) $funcionario_id;
        $stmt->bind_param('sssi', $modelo, $serie, $estado, $funcionario_id);

        if ($stmt->execute()) {
            echo json_encode([
                'ok' => true,
                'id' => $conexao->insert_id,
                'mensagem' => 'Trem cadastrado com sucesso! ID: ' . $conexao->insert_id
            ]);
            $stmt->close();
            exit;
        }

        $erro = 'Erro ao cadastrar trem: ' . $stmt->error;
        $stmt->close();
    }
}

http_response_code(400);
echo json_encode(['ok' => false, 'mensagem' => $erro]);