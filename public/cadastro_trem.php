<?php

include '../infra/conexao.php';

$sqlRotas = "SELECT id, nome FROM rotas";
$resultadoRotas = $conexao->query($sqlRotas);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $modelo = $_POST['modelo'];
    $rota_id = $_POST['rota_id'];

    $sql = "INSERT INTO trens (modelo, rota_id) values (?,?)";
    $stmt = $conexao->prepare($sql);
    $stmt -> bind_param('si', $modelo,$rota_id);
    if($stmt->execute()){
        echo "Trem cadastrado ";
        echo '<a href="cadastro_trem.php">voltar</a>';
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

                    <form id="FormsCadastro" method="POST">
                        <label for="modelo">Modelo</label>
                        <input type="text" id="cadastro-modelo" name="modelo" class="CadastroInput" placeholder="Modelo do trem">
                        <br>

                        <label for="rota_id">Rota</label>
                            <select id="rota_id" name="rota_id" class="CadastroInput" required>
                                <option value="">Selecione uma rota</option>
                                <?php while ($rota = $resultadoRotas->fetch_assoc()) { ?>
                                    <option value="<?= $rota['id'] ?>">
                                        <?= htmlspecialchars($rota['nome']) ?>
                                    </option>
                                <?php } ?>
                            </select>
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