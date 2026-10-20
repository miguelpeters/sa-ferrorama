<?php

include '../infra/conexao.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $modelo = $_POST['modelo'];
    $estacao_id = $_POST['rota_id'];
    $sensor_id = $_POST['sensor_id'];

    $sql = "INSERT INTO trem (modelo, rota_id, sensor_id) values (?,?,?)";
    $stmt = $conn-> prepare($sql);
    $stmt -> bind_param('ss', $modelo,$estacao_id,$sensor_id);

    if($stmt->execute()){
        echo "Trem cadastrado ";
        echo '<a href="../index.php">voltar</a>';
    }else{
        echo "Erro ao cadastrar" . $stmt->error;
    }
   $stmt->close();
   exit;

}

?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Trem</title>
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

                    <h2 id="CadastroTitulo">Cadastrar Trem</h2>

                    <form id="FormsCadastro">
                        <label for="modelo">Modelo</label>
                        <input type="text" id="cadastro-modelo" name="modelo" class="CadastroInput" placeholder="Modelo do trem">
                        <br>

                        <label for="estacao_id">Rota</label>
                        <input type="text" id="cadastro-estacao" name="estacao_id" class="CadastroInput" placeholder="Nome da rota">
                        <br>

                        <label for="sensor_id">Sensor</label>
                        <input type="text" id="cadastro-sensor" name="sensor_id" class="CadastroInput" placeholder="ID do sensor">
                        <br>
                        <br>
                        <button class="ButtonCadastro">CADASTRAR TREM</button>

                    </form>

                </div>

            </div>
        </div>

    </main>
</body>

</html>