<?php

include '../infra/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['numero_telefone'];
    $senha = $_POST['senha'];
    
    $hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO funcionarios
            (nome, email, numero_telefone, senha)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        'ssss',
        $nome,
        $email,
        $telefone,
        $hash
    );

    if ($stmt->execute()) {
        echo "Funcionário cadastrado com sucesso!";
        echo '<br><a href="cadastro_funcionario.php">Voltar</a>';
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
    exit;
}

?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionário</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="icon" href="../assets/imgs/PageIcon.png" type="image/x-icon">
</head>

<body class="bodyCadastro">

<div class="cadastro-detrain-topo">
    <a class="login-detrain-logo" href="home_gerente.php">DE-TRAIN</a>
    <img id="icon-login" src="../assets/imgs/LogoDeTrain.png">
  </div>
    <main>

        <div id="centrobloco">
            <div id="BlocoCadastro">

                <div class="Cadastro">

                    <h2 id="CadastroTitulo">Cadastrar Funcionário</h2>

                    <form id="FormsCadastro" method="POST">
                        <label for="nome" id="CadastroLabel">Nome</label>
                        <input type="text" id="cadastro-nome" name="nome" class="CadastroInput" placeholder="Nome do funcionário">
                        <br>

                        <label for="email" id="CadastroLabel">Email</label>
                        <input type="email" id="cadastro-email" name="email" class="CadastroInput" placeholder="Email do funcionário">
                        <br>

                        <label for="telefone" id="CadastroLabel">Telefone</label>
                        <input type="text" id="cadastro-telefone" name="numero_telefone" class="CadastroInput" placeholder="Telefone do funcionário">
                        <br>

                        <label for="senha" id="CadastroLabel">Senha</label>
                        <input type="password" id="cadastro-senha" name="senha" class="CadastroInput" placeholder="Insira senha do funcionário">
                        <br>
                        <br>
                        <button class="ButtonCadastro">CADASTRAR FUNCIONÁRIO</button>

                    </form>

                </div>

            </div>
        </div>

    </main>
</body>

</html>