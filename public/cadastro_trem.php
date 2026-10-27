<?php

include '../infra/conexao.php';

$sqlFuncionarios = "SELECT id, nome FROM funcionarios";
$resultadoFuncionarios = $conexao->query($sqlFuncionarios);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $modelo = trim($_POST['modelo'] ?? '');
    $serie = trim($_POST['serie'] ?? '');
    $estado = $_POST['estado'] ?? '';
    $funcionario_id = $_POST['funcionario_id'] ?? '';

    if ($modelo === '' || $serie === '' || $estado === '' || $funcionario_id === '') {
        exit("Preencha todos os campos.");
    }

    $sql = "INSERT INTO trens (modelo, serie, estado, funcionario_id)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        exit("Erro ao conectar com o banco: " . $conexao->error);
    }

    $stmt->bind_param('sssi', $modelo, $serie, $estado, $funcionario_id);

    if ($stmt->execute()) {
        echo "Trem cadastrado com sucesso! ";
        echo '<a href="cadastro_trem.php">Voltar</a>';
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
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
    <a class="login-detrain-logo" href="home_gerente.php">DE-TRAIN</a>
    <img id="icon-login" src="../assets/imgs/LogoDeTrain.png">
  </div>
    <main>

        <div id="centrobloco">
            <div id="BlocoCadastro">

                <div class="Cadastro">

                    <h2 id="CadastroTitulo">Cadastrar Trem</h2>

                    <form id="FormsCadastro" method="POST">
                    <label for="cadastro-modelo">Modelo</label>
                    <input type="text" id="cadastro-modelo" name="modelo"
                    class="CadastroInput" placeholder="Modelo do trem" required>
                    <br><br>

                    <label for="cadastro-serie">Série</label>
                    <input type="text" id="cadastro-serie" name="serie"
                    class="CadastroInput" placeholder="Série do trem" required>
                    <br><br>

                    <label for="estado">Estado</label>
                    <select id="estado" name="estado" class="CadastroInput" required>
                    <option value="">Selecione o estado</option>
                    <option value="operante">Operante</option>
                    <option value="inativo">Inativo</option>
                    </select>
                    <br><br>

                   <label for="funcionario_id">ID do Funcionário</label>
                    <input type="number" id="funcionario_id" name="funcionario_id"
                    class="CadastroInput" placeholder="ID do funcionário" min="1" required>
                    <br><br>

        <button type="submit" class="ButtonCadastro">
          CADASTRAR TREM
        </button>           

                    </form>

                </div>

            </div>
        </div>

    </main>
</body>

</html>