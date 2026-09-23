<?php

$servidor = "localhost";
$usuario = "root";
$senhaBanco = "";
$banco = "ferrorama_db"; 

$conn = new mysqli($servidor, $usuario, $senhaBanco, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["numero_telefone"];
    $senha = $_POST["senha"];

    $sql = "INSERT INTO usuarios (nome, email, numero_telefone, senha)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssss", $nome, $email, $telefone, $senha);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar usuário.');</script>";
    }

    $stmt->close();
}


?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de usuário</title>
    <link rel="stylesheet" href="../assets/style/style.css">

    <link rel="icon" href="../assets/imgs/LogoDeTrain.png" type="image/x-icon">
</head>

<body class="bodyCadastro">

<div class="cadastro-detrain-topo">
    <a class="login-detrain-logo" href="../index.php">DE-TRAIN</a>
    <img id="icon-login" src="../assets/imgs/LogoDeTrain.png">
  </div>
    <main>

        <div id="centrobloco">
            <div id="BlocoCadastro">

                <div class="Cadastro">

                    <h2 id="CadastroTitulo">Cadastro de usuário</h2>

                    <form id="FormsCadastro" method="POST">
                        <label for="nome" id="CadastroLabel">Nome</label>
                        <br>
                        <input type="text" id="cadastro-nome" name="nome" class="CadastroInput" placeholder="Seu nome">
                        <br>

                        <label for="email" id="CadastroLabel">Email</label>
                        <br>
                        <input type="email" id="cadastro-email" name="email" class="CadastroInput" placeholder="Seu email">
                        <br>

                        <label for="telefone" id="CadastroLabel">Telefone</label>
                        <br>
                        <input type="text" id="cadastro-telefone" name="numero_telefone" class="CadastroInput" placeholder="Seu telefone">
                        <br>

                        <label for="senha" id="CadastroLabel">Senha</label>
                        <br>
                        <input type="password" id="cadastro-senha" name="senha" class="CadastroInput" placeholder="Sua senha">
                        <br>
    
                        <button class="ButtonCadastro">CADASTRAR USUÁRIO</button>

                    </form>

                </div>

            </div>
        </div>

    </main>

    <script src="../assets/js/cadastro.js"></script>

</body>

</html>