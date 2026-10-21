
<?php
require_once __DIR__ . '/../infra/conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $tipo = trim($_POST["tipo"] ?? "");

    if ($nome === "" || $tipo === "") {
        $erro = "Preencha o nome e o tipo do sensor.";
    } else {
        $sql = "INSERT INTO sensores (nome, tipo) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);

        if (!$stmt) {
            $erro = "Erro ao preparar cadastro.";
        } else {
            $stmt->bind_param("ss", $nome, $tipo);

            if ($stmt->execute()) {
                $sucesso = "Sensor cadastrado com sucesso! ID: "
                    . $conexao->insert_id;
            } else {
                $erro = "Erro ao cadastrar sensor.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Sensor</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
<body class="bodyCadastro">

    <main>
        <div id="centrobloco">
            <div id="BlocoCadastro">
                <div class="Cadastro">
                    <h2 id="CadastroTitulo">Cadastrar Sensor</h2>

                    <?php if (isset($sucesso)): ?>
                        <p><?= htmlspecialchars($sucesso) ?></p>
                    <?php endif; ?>

                    <?php if (isset($erro)): ?>
                        <p><?= htmlspecialchars($erro) ?></p>
                    <?php endif; ?>

                    <form id="FormsCadastro"
                          action="cadastro_sensor.php"
                          method="POST">

                        <label class="label-sensor" for="cadastro-nome">
                            Nome
                        </label>
                        <input
                            type="text"
                            id="cadastro-nome"
                            name="nome"
                            class="CadastroInput"
                            placeholder="Nome do sensor"
                            maxlength="50"
                            required
                        >

                        <br>

                        <label class="label-sensor" for="cadastro-tipo">
                            Tipo
                        </label>
                        <input
                            type="text"
                            id="cadastro-tipo"
                            name="tipo"
                            class="CadastroInput"
                            placeholder="Tipo do sensor"
                            maxlength="50"
                            required
                        >

                        <br><br>

                        <button type="submit" class="ButtonCadastro">
                            CADASTRAR SENSOR
                        </button>
                    </form>

                    <p><a href="home_gerente.php">Voltar</a></p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
