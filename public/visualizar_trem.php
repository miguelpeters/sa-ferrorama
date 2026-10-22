
<?php

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
        "DELETE FROM trens WHERE id = ?"
    );

    if (!$stmt) {
        die("Erro ao preparar exclusão: " . $conexao->error);
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die("Erro ao excluir trem: " . $stmt->error);
    }

    $stmt->close();

    header("Location: visualizar_trem.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["atualizar"])) {

    $id = (int) $_POST["id"];
    $modelo = trim($_POST["modelo"]);

    $rota_id = ($_POST["rota_id"] === "")
        ? null
        : (int) $_POST["rota_id"];

    if ($rota_id === null) {

        $stmt = $conexao->prepare(
            "UPDATE trens
             SET modelo = ?, rota_id = NULL
             WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar atualização: " . $conexao->error);
        }

        $stmt->bind_param("si", $modelo, $id);

    } else {

        $stmt = $conexao->prepare(
            "UPDATE trens
             SET modelo = ?, rota_id = ?
             WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar atualização: " . $conexao->error);
        }

        $stmt->bind_param("sii", $modelo, $rota_id, $id);
    }

    if (!$stmt->execute()) {
        die("Erro ao atualizar trem: " . $stmt->error);
    }

    $stmt->close();

    header("Location: visualizar_trem.php");
    exit();
}

$tremEditar = null;

if (isset($_GET["editar"])) {

    $id = (int) $_GET["editar"];

    $stmt = $conexao->prepare(
        "SELECT id, modelo, rota_id
         FROM trens
         WHERE id = ?"
    );

    if (!$stmt) {
        die("Erro ao preparar consulta: " . $conexao->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultadoEditar = $stmt->get_result();

    if ($resultadoEditar->num_rows == 1) {
        $tremEditar = $resultadoEditar->fetch_assoc();
    }

    $stmt->close();
}

?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/style/style.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    >

    <title>Visualização de trens</title>

    <link
        rel="icon"
        href="../assets/imgs/LogoDeTrain.png"
        type="image/x-icon"
    >
</head>

<body class="body_home">

    <header class="cabecalho">

        <div class="lado-esquerdo">

            <button
                class="buttonRetornar"
                onclick="history.back()"
            >
                DE-TRAIN
            </button>

            <img
                id="icon"
                src="../assets/imgs/LogoDeTrain.png"
                class="logo"
            >

            <br>

        </div>

        <div class="lado-direito">

            <span>FULANO DE TAL - FUNCIONÁRIO</span>

            <img
                src="../assets/imgs/LoginIcon.png"
                class="perfil"
            >

        </div>

    </header>

    <main>

        <?php if ($tremEditar != null) { ?>

            <div class="container_editar_usuario">

                <div class="texto-editar-usuario">
                    <h2>
                        Editar trem
                    </h2>
                </div>

                <form method="POST">

                    <div class="form-editar-sensor">

                        <input
                            type="hidden"
                            name="id"
                            value="<?php echo $tremEditar["id"]; ?>"
                        >

                        <label for="modelo" class="editar-sensor">
                            Modelo
                        </label>

                        <br>

                        <input
                            class="input-editar-sensor"
                            type="text"
                            id="modelo"
                            name="modelo"
                            value="<?php echo htmlspecialchars($tremEditar["modelo"]); ?>"
                            required
                        >

                        <br><br>

                        <label for="rota_id" class="editar-sensor">
                            ID da rota
                        </label>

                        <br>

                        <input
                            class="input-editar-sensor"
                            type="number"
                            id="rota_id"
                            name="rota_id"
                            min="1"
                            value="<?php echo htmlspecialchars((string)($tremEditar["rota_id"] ?? "")); ?>"
                        >

                        <br><br>

                        <button
                            class="button-atualizar"
                            type="submit"
                            name="atualizar"
                        >
                            ATUALIZAR
                        </button>

                        <a
                            href="visualizar_trem.php"
                            class="button-cancelar"
                        >
                            CANCELAR
                        </a>

                    </div>

                </form>

            </div>

            <hr>

        <?php } ?>

        <div class="container_tabela_usuarios">

            <table class="tabela_usuarios table-bordered">

                <thead>

                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">ID do trem</th>
                        <th scope="col">ID da rota</th>
                        <th scope="col">Ações</th>
                    </tr>

                </thead>

                <tbody>

                    <?php

                    $sql = "
                        SELECT modelo, id, rota_id
                        FROM trens
                        ORDER BY modelo
                    ";

                    $resultado = $conexao->query($sql);

                    if (!$resultado) {
                        die("Erro ao buscar trens: " . $conexao->error);
                    }

                    while ($trem = $resultado->fetch_assoc()) {

                    ?>

                        <tr class="visualizar_usuario_gerente">

                            <th scope="row">
                                <?php echo htmlspecialchars($trem["id"]); ?>
                            </th>

                            <td>
                                <?php echo htmlspecialchars($trem["modelo"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($trem["id"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars((string)($trem["rota_id"] ?? "")); ?>
                            </td>

                            <td>

                                <a href="visualizar_trem.php?editar=<?php echo $trem["id"]; ?>">
                                    EDITAR
                                </a>

                                <form method="POST" style="display:inline;">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php echo $trem["id"]; ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="excluir"
                                        onclick="return confirm('Tem certeza que deseja excluir este trem?');"
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
    $conexao->close();
    ?>

</body>

</html>
