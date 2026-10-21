<?php

$servidor = localhost;
$usuarioBanco = "root";
$senhaBanco = "";
$banco = "ferrorama_db";

$conexao = new mysqli(
    $servidor,
    $usuarioBanco,
    $senhaBanco,
    $banco
);

?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização de sensores</title>

    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="icon" href="../assets/imgs/LogoDeTrain.png" type="image/x-icon">
</head>

<body>

    <header>
        <nav>

            <h1 class="DE-TRAIN">DE-TRAIN</h1>
            <img id="icon" src="../assets/imgs/LogoDeTrain.png">
            <h1><a class="EntrarText" href="../public/home.html">VOLTAR</a></h1>
            <img id="LoginIcon" src="../assets/imgs/VoltarIcon.png">

        </nav>
    </header>

    <main>

        <div class="container_tabela_usuarios">

            <table class="tabela_usuarios table-bordered">

                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">ID</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Trem</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Sensor 1</td>
                        <td>12345</td>
                        <td>Velocidade</td>
                        <td>Trem LN</td>
                    </tr>

                    <tr>
                        <th scope="row">2</th>
                        <td>Sensor 2</td>
                        <td>67890</td>
                        <td>Localização</td>
                        <td>Trem TC</td>
                    </tr>

                    <tr>
                        <th scope="row">3</th>
                        <td>Sensor 3</td>
                        <td>54321</td>
                        <td>Localização</td>
                        <td>Trem MK</td>
                    </tr>

                    <tr>
                        <th scope="row">4</th>
                        <td>Sensor 4</td>
                        <td>09876</td>
                        <td>Temperatuta</td>
                        <td>Trem TM</td>
                    </tr>

                    <tr>
                        <th scope="row">5</th>
                        <td>Sensor 5</td>
                        <td>23456</td>
                        <td>Pressão</td>
                        <td>Trem AD</td>
                    </tr>

                </tbody>

            </table>

        </div>

    </main>

</body>

</html>