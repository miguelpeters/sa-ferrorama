<?php

session_start();

$servidor = "localhost";
$usuario = "root";
$senhaBanco = "";
$banco = "ferrorama_db";

$conn = new mysqli($servidor, $usuario, $senhaBanco, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com o banco: " . $conn->connect_error);
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $sql = "SELECT id, nome, email, senha
            FROM gerentes
            WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {

        $gerente = $resultado->fetch_assoc();

        if ($senha === $gerente["senha"]) {

            $_SESSION["gerente_id"] = $gerente["id"];
            $_SESSION["gerente_nome"] = $gerente["nome"];
            $_SESSION["gerente_email"] = $gerente["email"];

            header("Location: home_gerente.php");
            exit();

        } else {
            $erro = "Email ou senha incorretos.";
        }

     } else {

        $sql = "SELECT id, nome, email, senha
                FROM funcionarios
                WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {

            $funcionario = $resultado->fetch_assoc();

            if ($senha === $funcionario["senha"]) {

                $_SESSION["tipo"] = "funcionario";
                $_SESSION["id"] = $funcionario["id"];
                $_SESSION["nome"] = $funcionario["nome"];
                $_SESSION["email"] = $funcionario["email"];

                header("Location: home_funcionario.php");
                exit();

            } else {
                $erro = "Email ou senha incorretos.";
            }

        } else {

            $sql = "SELECT id, nome, email, senha
                    FROM usuarios
                    WHERE email = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            if ($resultado->num_rows == 1) {

                $usuario = $resultado->fetch_assoc();

                if ($senha === $usuario["senha"]) {

                    $_SESSION["tipo"] = "usuario";
                    $_SESSION["id"] = $usuario["id"];
                    $_SESSION["nome"] = $usuario["nome"];
                    $_SESSION["email"] = $usuario["email"];

                    header("Location: home_logado.php");
                    exit();

                } else {
                    $erro = "Email ou senha incorretos.";
                }

            } else {

                $erro = "Email ou senha incorretos.";

            }
        }
    }

    $stmt->close();
}

$conn->close();

?>


<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style/style.css">

    <link rel="icon" href="../assets/imgs/PageIcon.png" type="image/x-icon">
</head>

<body class="bodyLogin">
<div class="login-detrain-topo">
    <a class="login-detrain-logo" href="../index.php">DE-TRAIN</a>
    <img id="icon-login" src="../assets/imgs/LogoDeTrain.png">
    
  </div>

  <div class="login-detrain-container">

    <div class="login-detrain-caixa">
      <p class="login-detrain-titulo">BEM <span class="verde">VINDO!</span></p>
      <p class="login-detrain-subtexto">Faça login para acessar o sistema e gerenciar seus trilhos.</p>

      <form action="login.php" method="post">

        <label for="email">Email</label><br>
        <input type="text" id="login-email" name="email" placeholder="seuemail@exemplo.com"><br><br>

        <label for="senha">Senha</label><br>
        <input type="password" id="login-senha" name="senha" placeholder="Sua Senha"><br><br>

        <p class="login-detrain-erro" id="mensagem-erro"></p>

        <button type="submit" class="login-detrain-botao">ENTRAR</button>

      </form>

      <p class="login-detrain-cadastro">
        Não tem uma conta? <a href="cadastro_usuario.php">Cadastre-se.</a>
      </p>
    </div>

    <div class="login-detrain-imagem">
      <img src="../assets/imgs/Imagem-login.png" alt="foto do trem">
    </div>

 <footer class= "login-rodape">

 </footer>


  <script src="../assets/js/login.js"></script>
</body>
</html>