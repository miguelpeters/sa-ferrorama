<?php
session_start();
include_once("../infra/conexao.php");
include_once("../infra/lembrar_login.php");

restaurarLogin($conexao);

if (
    !isset($_SESSION["id"]) ||
    !isset($_SESSION["tipo"]) ||
    $_SESSION["tipo"] !== "usuario"
) {
    header("Location: login.php");
    exit();
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style/style.css">
    <title>Home Gerente</title>

    <link rel="icon" href="../assets/imgs/PageIcon.png" type="image/x-icon">
</head>

<body class="body_home">

    <header class="cabecalho">
    
    <div class="lado-esquerdo">
        <a class="home-detrain-logo" href="../index.php">DE-TRAIN</a>
        <img id="icon" src="../assets/imgs/LogoDeTrain.png" class="logo">


        <nav class="menu">
        <a class="home-detrain-logo" href="../index.php">CADASTRAR FUNCIONÁRIO</a>
        <a class="home-detrain-logo" href="cadastro_trem.php">CADASTRAR TREM</a>
        <a class="home-detrain-logo" href="cadastro_estacao.php">CADASTRAR ESTAÇÃO</a>
        <a class="home-detrain-logo" href="cadastro_sensor.php">CADASTRAR SENSOR</a>
        <a class="home-detrain-logo" href="visualizar_sensor.php">VISUALIZAR SENSOR</a>
        <a class="home-detrain-logo" href="visualizar_usuario.php">VISUALIZAR USUÁRIO</a>
        </nav>
    </div>


    <div class="lado-direito">

    <?php if (isset($_SESSION["gerente_id"])): ?>

        <h1>
            Olá, <?= htmlspecialchars($_SESSION["gerente_nome"]) ?>
        </h1>
        <a href="logout.php">SAIR</a>

    <?php elseif (isset($_SESSION["id"])): ?>

        <h1>
            Olá, <?= htmlspecialchars($_SESSION["nome"]) ?>
        </h1>
        <a href="logout.php">SAIR</a>

    <?php else: ?>

        <h1>
            <a class="EntrarText_home" href="login.php">ENTRAR</a>
        </h1>

    <?php endif; ?>

    <img src="../assets/imgs/LoginIcon.png" class="perfil">

    </div>  

</header>

<main class="main_home">
    <img src="../assets/imgs/sistema-metrô-joinvilense.webp" alt="Linhas" class="linhas_home">
</main>


<footer class="footer_home">

        <h2 class="slogan_footer_home">Melhor ir DE-TRAIN!</h2>


</footer>

</body>




</html>