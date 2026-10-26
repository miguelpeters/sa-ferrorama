
<?php
session_start();

include_once("../infra/conexao.php");
include_once("../infra/lembrar_login.php");

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($email === "" || $senha === "") {
        $erro = "Preencha o email e a senha.";
    } else {

        $tabelas = [
            "gerentes" => "gerente",
            "funcionarios" => "funcionario"
        ];

        $loginRealizado = false;

        foreach ($tabelas as $tabela => $tipo) {

            $sql = "SELECT id, nome, email, senha
                    FROM $tabela
                    WHERE email = ?";

            $stmt = $conexao->prepare($sql);

            if (!$stmt) {
                error_log("Erro SQL em $tabela: " . $conexao->error);
                $erro = "Erro ao consultar as contas.";
                break;
            }

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            while ($conta = $resultado->fetch_assoc()) {

                $senhaBanco = $conta["senha"];

                // Verifica senhas que já estão em hash.
                $senhaValida = password_verify(
                    $senha,
                    $senhaBanco
                );

                // Compatibilidade com senhas antigas em texto puro.
                if (
                    !$senhaValida &&
                    hash_equals($senhaBanco, $senha)
                ) {
                    $novoHash = password_hash(
                        $senha,
                        PASSWORD_DEFAULT
                    );

                    $sqlAtualizar = "UPDATE $tabela
                                     SET senha = ?
                                     WHERE id = ?";

                    $stmtAtualizar = $conexao->prepare(
                        $sqlAtualizar
                    );

                    if ($stmtAtualizar) {

                        $stmtAtualizar->bind_param(
                            "si",
                            $novoHash,
                            $conta["id"]
                        );

                        if ($stmtAtualizar->execute()) {
                            $senhaValida = true;
                        }

                        $stmtAtualizar->close();
                    }
                }

                if (!$senhaValida) {
                    continue;
                }

                $loginRealizado = true;

                // Cria uma nova sessão após autenticar.
                $_SESSION = [];
                session_regenerate_id(true);

                $_SESSION["tipo"] = $tipo;

                if ($tipo === "gerente") {

                    $_SESSION["gerente_id"] = $conta["id"];
                    $_SESSION["gerente_nome"] = $conta["nome"];
                    $_SESSION["gerente_email"] = $conta["email"];

                } else {

                    $_SESSION["id"] = $conta["id"];
                    $_SESSION["nome"] = $conta["nome"];
                    $_SESSION["email"] = $conta["email"];

                }

                // Mantém a funcionalidade de login persistente.
                lembrarLogin(
                    $conexao,
                    $tipo,
                    $conta["id"]
                );

                $stmt->close();
                $conexao->close();

                if ($tipo === "gerente") {
                    header("Location: home_gerente.php");
                } else {
                    header("Location: home_funcionario.php");
                }

                exit();
            }

            $stmt->close();

            if ($loginRealizado) {
                break;
            }
        }

        if (!$loginRealizado && $erro === "") {
            $erro = "Email ou senha incorretos.";
        }
    }
}
?>

<?php if ($erro !== ""): ?>
    <script>
        alert(<?= json_encode($erro) ?>);
    </script>
<?php endif; ?>

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

        <label for="login-email">Email</label><br>
<input
    type="email"
    id="login-email"
    name="email"
    placeholder="seuemail@exemplo.com"
    required
><br><br>

<label for="login-senha">Senha</label><br>
<input
    type="password"
    id="login-senha"
    name="senha"
    placeholder="Sua senha"
    required
><br><br>
        <p class="login-detrain-erro" id="mensagem-erro"></p>

        <button type="submit" class="login-detrain-botao">ENTRAR</button>

      </form>

      <p class="login-detrain-cadastro">
      
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