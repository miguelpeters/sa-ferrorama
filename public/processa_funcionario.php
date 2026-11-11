<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (($_SESSION['tipo'] ?? '') !== 'gerente') {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensagem' => 'Sessão expirada ou sem permissão.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

include '../infra/conexao.php';

$nome     = trim($_POST['nome'] ?? '');
$email    = trim($_POST['email'] ?? '');
$telefone = trim($_POST['numero_telefone'] ?? '');
$senha    = $_POST['senha'] ?? '';

if ($nome === '' || $email === '' || $telefone === '' || $senha === '') {
    $erro = 'Preencha todos os campos.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erro = 'Informe um email válido.';
} else {
    $hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conexao->prepare(
        "INSERT INTO funcionarios (nome, email, numero_telefone, senha) VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        $erro = 'Erro ao preparar cadastro: ' . $conexao->error;
    } else {
        $stmt->bind_param('ssss', $nome, $email, $telefone, $hash);

        if ($stmt->execute()) {
            echo json_encode([
                'ok' => true,
                'id' => $conexao->insert_id,
                'mensagem' => 'Funcionário cadastrado com sucesso! ID: ' . $conexao->insert_id
            ]);
            $stmt->close();
            exit;
        }

        // 1062 = valor duplicado (caso a coluna email seja única no banco)
        $erro = $stmt->errno === 1062
            ? 'Já existe um funcionário com esse email.'
            : 'Erro ao cadastrar funcionário.';

        $stmt->close();
    }
}

http_response_code(400);
echo json_encode(['ok' => false, 'mensagem' => $erro]);