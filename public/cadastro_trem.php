<?php

include '../infra/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $modelo = trim($_POST['modelo'] ?? '');
    $serie = trim($_POST['serie'] ?? '');
    $estado = $_POST['estado'] ?? '';
    $funcionario_id = $_POST['funcionario_id'] ?? '';

    if (
        $modelo === '' ||
        $serie === '' ||
        $estado === '' ||
        $funcionario_id === ''
    ) {
        $erro = "Preencha todos os campos.";
    } elseif (!in_array($estado, ['operante', 'inativo'], true)) {
        $erro = "Estado inválido.";
    } elseif (!ctype_digit((string) $funcionario_id) || (int) $funcionario_id < 1) {
        $erro = "Selecione um funcionário válido.";
    } else {

        $sql = "INSERT INTO trens (modelo, serie, estado, funcionario_id)
                VALUES (?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        if (!$stmt) {
            $erro = "Erro ao preparar cadastro: " . $conexao->error;
        } else {

            $funcionario_id = (int) $funcionario_id;

            $stmt->bind_param(
                "sssi",
                $modelo,
                $serie,
                $estado,
                $funcionario_id
            );

            if ($stmt->execute()) {
                $sucesso = "Trem cadastrado com sucesso! ID: "
                    . $conexao->insert_id;
            } else {
                $erro = "Erro ao cadastrar trem: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

$sqlFuncionarios = "SELECT id, nome FROM funcionarios ORDER BY nome";
$resultadoFuncionarios = $conexao->query($sqlFuncionarios);

?>

<!DOCTYPE html>
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

                    <?php if (isset($sucesso)) { ?>
                        <p><?= htmlspecialchars($sucesso) ?></p>
                    <?php } ?>

                    <?php if (isset($erro)) { ?>
                        <p><?= htmlspecialchars($erro) ?></p>
                    <?php } ?>

                    <form id="FormsCadastro" method="POST">

                        <label for="cadastro-modelo" id="CadastroLabel">
                            Modelo
                        </label>
                        <input
                            type="text"
                            id="cadastro-modelo"
                            name="modelo"
                            class="CadastroInput"
                            placeholder="Modelo do trem"
                            required
                        >
                        <br><br>

                        <label for="cadastro-serie" id="CadastroLabel">
                            Série
                        </label>
                        <input
                            type="text"
                            id="cadastro-serie"
                            name="serie"
                            class="CadastroInput"
                            placeholder="Série do trem"
                            required
                        >
                        <br><br>

                        <label for="estado" id="CadastroLabel">
                            Estado
                        </label>
                        <select
                            id="estado"
                            name="estado"
                            class="CadastroInput"
                            required
                        >
                            <option value="">Selecione o estado</option>
                            <option value="operante">Operante</option>
                            <option value="inativo">Inativo</option>
                        </select>
                        <br><br>

                        <label for="funcionario_id" id="CadastroLabel">ID do Funcionário</label>
                        <input
                            type="number"
                            id="funcionario_id"
                            name="funcionario_id"
                            class="CadastroInput"
                            placeholder="ID do funcionário"
                            min="1"
                            required
>
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