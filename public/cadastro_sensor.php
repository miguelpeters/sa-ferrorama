
<?php
require_once __DIR__ . '/../infra/conexao.php';

$erro = "";
$sucesso = "";


$sqlRotas = "SELECT id, nome FROM rotas ORDER BY nome";
$resultadoRotas = $conexao->query($sqlRotas);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $tipo = trim($_POST["tipo"] ?? "");
    $rota_id = filter_input(INPUT_POST, "rota_id", FILTER_VALIDATE_INT);

    if ($nome === "" || $tipo === "" || !$rota_id) {
        $erro = "Preencha todos os campos e selecione uma rota.";
    } else {
        
        $verifica = $conexao->prepare(
            "SELECT id FROM rotas WHERE id = ?"
        );
        $verifica->bind_param("i", $rota_id);
        $verifica->execute();
        $rotaExiste = $verifica->get_result()->num_rows > 0;
        $verifica->close();

        if (!$rotaExiste) {
            $erro = "A rota selecionada não existe.";
        } else {
            $sql = "INSERT INTO sensores (nome, tipo, rota_id)
                    VALUES (?, ?, ?)";

            $stmt = $conexao->prepare($sql);

            if (!$stmt) {
                $erro = "Erro ao preparar cadastro: " . $conexao->error;
            } else {
                $stmt->bind_param("ssi", $nome, $tipo, $rota_id);

                if ($stmt->execute()) {
                    $sucesso = "Sensor cadastrado com sucesso! ID: "
                        . $conexao->insert_id;
                } else {
                    $erro = "Erro ao cadastrar sensor: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    }

    
    $resultadoRotas = $conexao->query($sqlRotas);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Sensor</title>
    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="icon" href="../assets/imgs/LogoDeTrain.png">
</head>

<body class="bodyCadastro">

    <div class="cadastro-detrain-topo">
        <a class="login-detrain-logo" href="../index.php">DE-TRAIN</a>
        <img id="icon-login" src="../assets/imgs/LogoDeTrain.png" alt="Logo DE-TRAIN">
    </div>

    <main>
        <div id="centrobloco">
            <div id="BlocoCadastro">
                <div class="Cadastro">

                    <h2 id="CadastroTitulo">Cadastrar Sensor</h2>

                    <?php if ($sucesso !== ""): ?>
                        <p><?= htmlspecialchars($sucesso) ?></p>
                    <?php endif; ?>

                    <?php if ($erro !== ""): ?>
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
                        <br>

                        <label class="label-sensor" for="rota_id">
                            Rota
                        </label>

                        <select
                            id="rota_id"
                            name="rota_id"
                            class="CadastroInput"
                            required
                        >
                            <option value="">Selecione uma rota</option>

                            <?php if ($resultadoRotas): ?>
                                <?php while ($rota = $resultadoRotas->fetch_assoc()): ?>
                                    <option value="<?= (int) $rota['id'] ?>"
                                        <?= isset($rota_id) && $rota_id == $rota['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($rota['nome']) ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>

                        <?php if ($resultadoRotas && $resultadoRotas->num_rows === 0): ?>
                            <p>Nenhuma rota cadastrada. Cadastre uma rota primeiro.</p>
                        <?php endif; ?>

                        <br><br>

                        <button
                            type="submit"
                            class="ButtonCadastro"
                            <?= !$resultadoRotas || $resultadoRotas->num_rows === 0 ? 'disabled' : '' ?>
                        >
                            CADASTRAR SENSOR
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </main>

</body>
</html>

<?php
$conexao->close();
?>
