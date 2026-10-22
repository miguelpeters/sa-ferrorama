<?php

session_start();

include '../infra/conexao.php';

$tipoUsuario = $_SESSION['tipo'] ?? '';

$podeGerenciarRotas = in_array(
    $tipoUsuario,
    ['gerente', 'funcionario'],
    true
);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["excluir"])) {

    if (!$podeGerenciarRotas) {
        http_response_code(403);
        exit("Você não tem permissão para excluir rotas.");
    }

    $id = (int) $_POST["id"];

    $stmt = $conexao->prepare(
        "DELETE FROM rotas WHERE id = ?"
    );

    if (!$stmt) {
        die("Erro ao preparar exclusão: " . $conexao->error);
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        die("Erro ao excluir rota: " . $stmt->error);
    }

    $stmt->close();

    header("Location: visualizar_rota.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["atualizar"])) {

    if (!$podeGerenciarRotas) {
        http_response_code(403);
        exit("Você não tem permissão para editar rotas.");
    }

    $id = (int) $_POST["id"];
    $nome = trim($_POST["nome"]);

    $stmt = $conexao->prepare(
        "UPDATE rotas SET nome = ? WHERE id = ?"
    );

    if (!$stmt) {
        die("Erro ao preparar atualização: " . $conexao->error);
    }

    $stmt->bind_param("si", $nome, $id);

    if (!$stmt->execute()) {
        die("Erro ao atualizar rota: " . $stmt->error);
    }

    $stmt->close();

    header("Location: visualizar_rota.php");
    exit();
}

$rotaEditar = null;

if ($podeGerenciarRotas && isset($_GET["editar"])) {

    $id = (int) $_GET["editar"];

    $stmt = $conexao->prepare(
        "SELECT id, nome FROM rotas WHERE id = ?"
    );

    if (!$stmt) {
        die("Erro ao preparar consulta: " . $conexao->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultadoEditar = $stmt->get_result();

    if ($resultadoEditar->num_rows == 1) {
        $rotaEditar = $resultadoEditar->fetch_assoc();
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">

    <link rel="icon" href="../assets/imgs/LogoDeTrain.png" type="image/x-icon">

    <title>Visualização de rotas</title>
</head>

<body class="body_home">

<header class="cabecalho">

    <div class="lado-esquerdo">
        <button class="buttonRetornar" onclick="history.back()">
            DE-TRAIN
        </button>

        <img id="icon"
             src="../assets/imgs/LogoDeTrain.png"
             class="logo">
    </div>

    <div class="lado-direito">
        <span>FULANO DE TAL - FUNCIONÁRIO</span>
        <img src="../assets/imgs/LoginIcon.png" class="perfil">
    </div>

</header>

<main>

    <?php if ($podeGerenciarRotas && $rotaEditar != null) { ?>

        <div class="container_editar_usuario">

            <div class="texto-editar-usuario">
                <h2>Editar rota</h2>
            </div>

            <form method="POST">

                <div class="form-editar-sensor">

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $rotaEditar["id"]; ?>"
                    >

                    <label for="nome" class="editar-sensor">
                        Nome da rota
                    </label>

                    <br>

                    <input
                        class="input-editar-sensor"
                        type="text"
                        id="nome"
                        name="nome"
                        value="<?php echo htmlspecialchars($rotaEditar["nome"]); ?>"
                        maxlength="100"
                        required
                    >

                    <br><br>

                    <button
                        class="button-atualizar"
                        type="submit"
                        name="atualizar"
                    >
                        ATUALIZAR
                    </button>

                    <a href="visualizar_rota.php"
                       class="button-cancelar">
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
                    <th scope="col">ID</th>
                    <th scope="col">Nome da rota</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>

            <tbody>

                <?php

                $sql = "SELECT id, nome FROM rotas ORDER BY nome";
                $resultado = $conexao->query($sql);

                if (!$resultado) {
                    die("Erro ao buscar rotas: " . $conexao->error);
                }

                while ($rota = $resultado->fetch_assoc()) {

                ?>

                    <tr class="visualizar_usuario_gerente">

                        <th scope="row">
                            <?php echo htmlspecialchars($rota["id"]); ?>
                        </th>

                        <td>
                            <?php echo htmlspecialchars($rota["nome"]); ?>
                        </td>

                        <td>
                            <?php if ($podeGerenciarRotas) { ?>

                                <a href="visualizar_rota.php?editar=<?php echo $rota['id']; ?>">
                                    EDITAR
                                </a>

                                <form method="POST" style="display:inline;">
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php echo $rota['id']; ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="excluir"
                                        onclick="return confirm('Tem certeza que deseja excluir esta rota?');"
                                    >
                                        EXCLUIR
                                    </button>
                                </form>

                            <?php } ?>

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