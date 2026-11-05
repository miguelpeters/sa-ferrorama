<?php
session_start();

include_once("../infra/conexao.php");
include_once("../infra/lembrar_login.php");

restaurarLogin($conexao);

if (
    !isset($_SESSION["gerente_id"]) ||
    !isset($_SESSION["tipo"]) ||
    $_SESSION["tipo"] !== "gerente"
) {
    header("Location: login.php");
    exit();
}

$nomeGerente = htmlspecialchars($_SESSION["gerente_nome"] ?? "Gerente");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel do Gerente</title>

    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="icon" href="../assets/imgs/PageIcon.png" type="image/x-icon">
</head>

<body class="body_home">

    <header class="hg-header">

        <div class="hg-brand">
            <img src="../assets/imgs/LogoDeTrain.png" alt="Logo DE-TRAIN">
            <a href="../index.php">DE-TRAIN</a>
        </div>


        <div class="hg-profile">
            <div class="hg-profile-info">
                <span>Bem-vindo(a),</span>
                <strong><?= $nomeGerente ?></strong>
            </div>

            <a href="logout.php" class="hg-logout">Sair</a>
        </div>

    </header>


    <main class="hg-main">



        <section class="hg-dashboard">


            <aside class="hg-sidebar">

                <div class="hg-section-heading">
                    <h2>Dashboard</h2>
                    <span>Visão geral</span>
                </div>

                <div class="hg-stat-card">
                    <div class="hg-stat-icon">T</div>
                    <div>
                        <span>Trens cadastrados</span>
                        <strong>--</strong>
                    </div>
                </div>

                <div class="hg-stat-card">
                    <div class="hg-stat-icon">R</div>
                    <div>
                        <span>Rotas cadastradas</span>
                        <strong>--</strong>
                    </div>
                </div>

                <div class="hg-stat-card">
                    <div class="hg-stat-icon">S</div>
                    <div>
                        <span>Sensores cadastrados</span>
                        <strong>--</strong>
                    </div>
                </div>


                <div class="hg-operations-card">
                    <h3>Cadastros</h3>
                    <p>Utilize os atalhos para cadastrar novos registros no sistema.</p>

                    <a href="cadastro_trem.php">Cadastrar trem</a>
                    <a href="cadastro_rota.php">Cadastrar rota</a>
                    <a href="cadastro_sensor.php">Cadastrar sensor</a>
                    <a href="cadastro_funcionario.php">Cadastrar funcionário</a>
                </div>

            </aside>


            <section class="hg-map-section">

                <div class="hg-section-heading">
                    <div>
                        <h2>Mapa Ferroviário</h2>
                        <span>Visualização da malha ferroviária</span>
                    </div>

                    <span class="hg-map-tag">JOINVILLE - SC</span>
                </div>

                <div class="hg-map-container">

                    <img
                        src="../assets/imgs/sistema-metrô-joinvilense.webp"
                        alt="Mapa da malha ferroviária"
                        class="hg-map-image"
                    >

                </div>

               

            </section>



            <aside class="hg-alerts">

                <div class="hg-quick-access">
                    <h3>Visualizações</h3>

                    <a href="visualizar_sensor.php">
                        <span>Visualizar sensores</span>
                        <span>→</span>
                    </a>

                    <a href="visualizar_trem.php">
                        <span>Visualizar trens</span>
                        <span>→</span>
                    </a>

                    <a href="visualizar_rota.php">
                        <span>Visualizar rotas</span>
                        <span>→</span>
                    </a>

                    <a href="visualizar_usuario.php">
                        <span>Visualizar usuários</span>
                        <span>→</span>
                    </a>
                </div>

            </aside>

        </section>

    </main>


    <footer class="hg-footer">
        <strong>Melhor ir DE-TRAIN!</strong>
        <span>SA-Ferrorama | Sistema de gerenciamento ferroviário</span>
    </footer>

</body>
</html>