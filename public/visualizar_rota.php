<?php

session_start();

$tipoUsuario = $_SESSION["tipo"] ?? "";

if (!in_array($tipoUsuario, ["gerente", "funcionario"], true)) {
    header("Location: login.php");
    exit();
}

$paginaHome = ($tipoUsuario === "gerente")
    ? "home_gerente.php"
    : "home_funcionario.php";

$servidor = "localhost";
$usuarioBanco = "root";
$senhaBanco = "";
$banco = "ferrorama_db";

$conexao = new mysqli(
    $servidor,
    $usuarioBanco,
    $senhaBanco,
    $banco
);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco: " . $conexao->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["excluir"])) {

    $id = (int) $_POST["id"];

    $stmt = $conexao->prepare(
        "DELETE FROM sensores WHERE id = ?"
    );

    if (!$stmt) {
        die("Erro ao preparar exclusão: " . $conexao->error);
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die("Erro ao excluir sensor: " . $stmt->error);
    }

    $stmt->close();

    header("Location: visualizar_sensor.php");
    exit();
}

// ATUALIZAR SENSOR
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["atualizar"])) {

    $id = (int) $_POST["id"];
    $nome = trim($_POST["nome"]);
    $tipo = trim($_POST["tipo"]);

    $rota_id = ($_POST["rota_id"] === "")
        ? null
        : (int) $_POST["rota_id"];

    if ($rota_id === null) {

        $stmt = $conexao->prepare(
            "UPDATE sensores
             SET nome = ?, tipo = ?, rota_id = NULL
             WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar atualização: " . $conexao->error);
        }

        $stmt->bind_param("ssi", $nome, $tipo, $id);

    } else {

        $stmt = $conexao->prepare(
            "UPDATE sensores
             SET nome = ?, tipo = ?, rota_id = ?
             WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar atualização: " . $conexao->error);
        }

        $stmt->bind_param("ssii", $nome, $tipo, $rota_id, $id);
    }

    if (!$stmt->execute()) {
        die("Erro ao atualizar sensor: " . $stmt->error);
    }

    $stmt->close();

    header("Location: visualizar_sensor.php");
    exit();
}

// BUSCAR SENSOR PARA EDITAR
$sensorEditar = null;

if (isset($_GET["editar"])) {

    $id = (int) $_GET["editar"];

    $stmt = $conexao->prepare(
        "SELECT id, nome, tipo, rota_id
         FROM sensores
         WHERE id = ?"
    );

    if (!$stmt) {
        die("Erro ao preparar consulta: " . $conexao->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultadoEditar = $stmt->get_result();

    if ($resultadoEditar->num_rows == 1) {
        $sensorEditar = $resultadoEditar->fetch_assoc();
    }

    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap PRIMEIRO, style.css DEPOIS (assim o style.css prevalece) -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    >

    <link rel="stylesheet" href="../assets/style/style.css">

    <link
        rel="icon"
        href="../assets/imgs/LogoDeTrain.png"
        type="image/x-icon"
    >

    <title>Visualização de sensores</title>

</head>

<body class="body_home">

<header class="cabecalho">

    <div class="lado-esquerdo">

        <a
            class="login-detrain-logo"
            href="<?= htmlspecialchars($paginaHome) ?>"
        >VOLTAR</a>

        <img
            id="icon"
            src="../assets/imgs/LogoDeTrain.png"
            class="logo"
            alt="Logo DE-TRAIN"
        >

    </div>

</header>


<main class="pagina-rotas">


    <?php if ($sensorEditar != null) { ?>

        <div class="container_editar_usuario">

            <div class="texto-editar-usuario">
                <h2>Editar <?php echo htmlspecialchars($sensorEditar["tipo"]); ?></h2>
            </div>

            <form method="POST">

                <div class="form-editar-rota">

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo (int) $sensorEditar["id"]; ?>"
                    >

                    <label
                        for="nome"
                        class="editar-rota"
                    >
                        Nome
                    </label>

                    <input
                        class="input-editar-rota"
                        type="text"
                        id="nome"
                        name="nome"
                        value="<?php echo htmlspecialchars($sensorEditar["nome"]); ?>"
                        required
                    >

                    <label
                        for="tipo"
                        class="editar-rota"
                    >
                        Tipo
                    </label>

                    <input
                        class="input-editar-rota"
                        type="text"
                        id="tipo"
                        name="tipo"
                        value="<?php echo htmlspecialchars($sensorEditar["tipo"]); ?>"
                        required
                    >

                    <label
                        for="rota_id"
                        class="editar-rota"
                    >
                        ID da rota
                    </label>

                    <input
                        class="input-editar-rota"
                        type="number"
                        id="rota_id"
                        name="rota_id"
                        value="<?php echo htmlspecialchars((string) ($sensorEditar["rota_id"] ?? "")); ?>"
                    >

                    <div class="acoes-editar-rota">

                        <button
                            class="button-atualizar"
                            type="submit"
                            name="atualizar"
                        >
                            ATUALIZAR
                        </button>

                        <a
                            href="visualizar_sensor.php"
                            class="button-cancelar"
                        >
                            CANCELAR
                        </a>

                    </div>

                </div>

            </form>

        </div>

        <hr>

    <?php } ?>


    <!-- TABELA DE SENSORES -->

    <div class="container_tabelas">

        <table class="tabela_padrao">

            <thead>

                <tr>

                    <th scope="col">#</th>

                    <th scope="col">Nome</th>

                    <th scope="col">Tipo</th>

                    <th scope="col">ID do sensor</th>

                    <th scope="col">ID da rota</th>

                    <th scope="col">Ações</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $sql = "
                    SELECT nome, tipo, id, rota_id
                    FROM sensores
                    ORDER BY nome
                ";

                $resultado = $conexao->query($sql);

                if (!$resultado) {
                    die("Erro ao buscar sensores: " . $conexao->error);
                }

                while ($sensor = $resultado->fetch_assoc()) {

                ?>

                    <tr class="visualizar_usuario_gerente">

                        <th scope="row">
                            <?php echo htmlspecialchars($sensor["id"]); ?>
                        </th>

                        <td>
                            <?php echo htmlspecialchars($sensor["nome"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($sensor["tipo"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($sensor["id"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars((string) ($sensor["rota_id"] ?? "")); ?>
                        </td>

                        <td>

                            <a href="visualizar_sensor.php?editar=<?php echo (int) $sensor["id"]; ?>">
                                EDITAR
                            </a>

                            <form
                                method="POST"
                                style="display:inline;"
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?php echo (int) $sensor["id"]; ?>"
                                >

                                <button
                                    type="submit"
                                    name="excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este sensor?');"
                                >
                                    EXCLUIR
                                </button>

                            </form>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</main>


<?php

$resultado->free();

$conexao->close();

?>

</body>
</html>