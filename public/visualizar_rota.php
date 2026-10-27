<?php

session_start();

$tipoUsuario = $_SESSION["tipo"] ?? "";

if (!in_array($tipoUsuario, ["gerente", "funcionario"], true)) {
    header("Location: login.php");
    exit();
}

include_once("../infra/conexao.php");

$podeGerenciarRotas = true;

$mensagem = "";
$tipoMensagem = "";

// ==========================================
// EXCLUIR ROTA
// ==========================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["excluir"])
) {

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if (!$id || $id <= 0) {

        $mensagem = "ID da rota inválido.";
        $tipoMensagem = "danger";

    } else {

        $stmt = $conexao->prepare(
            "DELETE FROM rotas WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar exclusão: " . $conexao->error);
        }

        $stmt->bind_param("i", $id);

        try {

            $stmt->execute();

            if ($stmt->affected_rows > 0) {

                $stmt->close();

                header("Location: visualizar_rota.php?sucesso=excluir");
                exit();

            } else {

                $mensagem = "Rota não encontrada.";
                $tipoMensagem = "warning";

            }

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1451) {

                // Buscar os sensores associados à rota.

                $stmtSensores = $conexao->prepare(
                    "SELECT id, nome, tipo
                     FROM sensores
                     WHERE rota_id = ?"
                );

                if (!$stmtSensores) {
                    die("Erro ao consultar sensores: " . $conexao->error);
                }

                $stmtSensores->bind_param("i", $id);
                $stmtSensores->execute();

                $resultadoSensores = $stmtSensores->get_result();

                $sensoresEncontrados = [];

                while ($sensor = $resultadoSensores->fetch_assoc()) {

                    $sensoresEncontrados[] =
                        "ID " . $sensor["id"] .
                        " - " . $sensor["nome"] .
                        " (" . $sensor["tipo"] . ")";

                }

                $stmtSensores->close();

                if (!empty($sensoresEncontrados)) {

                    $mensagem =
                        "Não foi possível excluir esta rota!\n\n" .
                        "Ela possui os seguintes sensores vinculados:\n\n" .
                        implode("\n", $sensoresEncontrados);

                } else {

                    $mensagem =
                        "Não foi possível excluir esta rota, pois existem outros registros vinculados a ela.";

                }

                $tipoMensagem = "danger";

            } else {

                $mensagem = "Erro ao excluir a rota.";
                $tipoMensagem = "danger";

            }

        } finally {

            $stmt->close();

        }
    }
}


if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["atualizar"])
) {

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    $nome = trim($_POST["nome"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $distancia = $_POST["distancia"] ?? "";
    $duracao = $_POST["duracao"] ?? "";
    $dataCriacao = $_POST["data_criacao"] ?? "";

    $tremId = filter_input(
        INPUT_POST,
        "trem_id",
        FILTER_VALIDATE_INT
    );

    if (
        !$id ||
        $id <= 0 ||
        $nome === "" ||
        $distancia === "" ||
        $duracao === "" ||
        $dataCriacao === "" ||
        !$tremId ||
        $tremId <= 0
    ) {

        $mensagem = "Preencha todos os campos obrigatórios.";
        $tipoMensagem = "warning";

    } elseif (
        strlen($nome) > 100 ||
        !is_numeric($distancia) ||
        !is_numeric($duracao) ||
        (float) $distancia < 0 ||
        (float) $duracao < 0
    ) {

        $mensagem = "Confira o nome, a distância e a duração informados.";
        $tipoMensagem = "warning";

    } elseif (
        !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataCriacao) ||
        !checkdate(
            (int) substr($dataCriacao, 5, 2),
            (int) substr($dataCriacao, 8, 2),
            (int) substr($dataCriacao, 0, 4)
        )
    ) {

        $mensagem = "A data de criação informada é inválida.";
        $tipoMensagem = "warning";

    } else {

        $stmtTrem = $conexao->prepare(
            "SELECT id FROM trens WHERE id = ?"
        );

        if (!$stmtTrem) {
            die("Erro ao consultar trem: " . $conexao->error);
        }

        $stmtTrem->bind_param("i", $tremId);
        $stmtTrem->execute();

        $resultadoTrem = $stmtTrem->get_result();

        if ($resultadoTrem->num_rows === 0) {

            $mensagem = "O trem selecionado não existe.";
            $tipoMensagem = "warning";

        } else {

            $sql = "UPDATE rotas
                    SET nome = ?,
                        descricao = ?,
                        distancia = ?,
                        duracao = ?,
                        data_criacao = ?,
                        trem_id = ?
                    WHERE id = ?";

            $stmt = $conexao->prepare($sql);

            if (!$stmt) {
                die("Erro ao preparar atualização: " . $conexao->error);
            }

            $distancia = (float) $distancia;
            $duracao = (float) $duracao;

            $stmt->bind_param(
                "ssddsii",
                $nome,
                $descricao,
                $distancia,
                $duracao,
                $dataCriacao,
                $tremId,
                $id
            );

            if ($stmt->execute()) {

                if ($stmt->affected_rows >= 0) {

                    $stmt->close();
                    $stmtTrem->close();

                    header("Location: visualizar_rota.php?sucesso=atualizar");
                    exit();
                }

            } else {

                $mensagem = "Erro ao atualizar a rota.";
                $tipoMensagem = "danger";
            }

            $stmt->close();
        }

        $stmtTrem->close();
    }
}

$mensagemSucesso = "";

if (isset($_GET["sucesso"])) {

    if ($_GET["sucesso"] === "excluir") {

        $mensagemSucesso = "Rota excluída com sucesso!";

    } elseif ($_GET["sucesso"] === "atualizar") {

        $mensagemSucesso = "Rota atualizada com sucesso!";

    }
}

$rotaEditar = null;

if (isset($_GET["editar"])) {

    $idEditar = filter_input(
        INPUT_GET,
        "editar",
        FILTER_VALIDATE_INT
    );

    if ($idEditar && $idEditar > 0) {

        $stmt = $conexao->prepare(
            "SELECT id, nome, descricao, distancia,
                    duracao, data_criacao, trem_id
             FROM rotas
             WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar consulta: " . $conexao->error);
        }

        $stmt->bind_param("i", $idEditar);
        $stmt->execute();

        $resultadoEditar = $stmt->get_result();

        if ($resultadoEditar->num_rows === 1) {
            $rotaEditar = $resultadoEditar->fetch_assoc();
        }

        $stmt->close();
    }
}

$sqlTrens = "SELECT id, modelo FROM trens ORDER BY modelo";

$resultadoTrens = $conexao->query($sqlTrens);

if (!$resultadoTrens) {
    die("Erro ao buscar trens: " . $conexao->error);
}

$trens = [];

while ($trem = $resultadoTrens->fetch_assoc()) {
    $trens[] = $trem;
}

$sql = "SELECT
            rotas.id,
            rotas.nome,
            rotas.descricao,
            rotas.distancia,
            rotas.duracao,
            rotas.data_criacao,
            rotas.trem_id,
            trens.modelo AS modelo_trem
        FROM rotas
        INNER JOIN trens
            ON rotas.trem_id = trens.id
        ORDER BY rotas.nome";

$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Erro ao buscar rotas: " . $conexao->error);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/style/style.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    >

    <link
        rel="icon"
        href="../assets/imgs/LogoDeTrain.png"
        type="image/x-icon"
    >

    <title>Visualização de rotas</title>

</head>

<body class="body_home">

<?php if ($mensagem !== "") { ?>
    <script>
        alert(<?php echo json_encode($mensagem); ?>);
    </script>
<?php } ?>

<?php if ($mensagemSucesso !== "") { ?>
    <script>
        alert(<?php echo json_encode($mensagemSucesso); ?>);

        window.history.replaceState(
            null,
            "",
            window.location.pathname
        );
    </script>
<?php } ?>

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
            alt="Logo DE-TRAIN"
        >

    </div>

    <div class="lado-direito">

        <span>

            <?php

            echo htmlspecialchars(
                $_SESSION["gerente_nome"]
                ?? $_SESSION["nome"]
                ?? "USUÁRIO"
            );

            if ($tipoUsuario === "gerente") {
                echo " - GERENTE";
            } else {
                echo " - FUNCIONÁRIO";
            }

            ?>

        </span>

        <img
            src="../assets/imgs/LoginIcon.png"
            class="perfil"
            alt="Perfil"
        >

    </div>

</header>

<main>


    <?php if ($mensagem !== "") { ?>

    <p>
        <?php echo htmlspecialchars($mensagem); ?>
    </p>

    <?php } ?>


    <?php if ($rotaEditar !== null) { ?>

        <div class="container_editar_usuario">

            <div class="texto-editar-usuario">

                <h2>Editar rota</h2>

            </div>

            <form method="POST">

                <div class="form-editar-sensor">

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo (int) $rotaEditar["id"]; ?>"
                    >

                    <label
                        for="nome"
                        class="editar-sensor"
                    >
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

                    <label
                        for="descricao"
                        class="editar-sensor"
                    >
                        Descrição
                    </label>

                    <br>

                    <textarea
                        class="input-editar-sensor"
                        id="descricao"
                        name="descricao"
                        rows="3"
                    ><?php echo htmlspecialchars($rotaEditar["descricao"] ?? ""); ?></textarea>

                    <br><br>

                    <label
                        for="distancia"
                        class="editar-sensor"
                    >
                        Distância
                    </label>

                    <br>

                    <input
                        class="input-editar-sensor"
                        type="number"
                        id="distancia"
                        name="distancia"
                        min="0"
                        step="0.01"
                        value="<?php echo htmlspecialchars($rotaEditar["distancia"]); ?>"
                        required
                    >

                    <br><br>

                    <label
                        for="duracao"
                        class="editar-sensor"
                    >
                        Duração (horas)
                    </label>

                    <br>

                    <input
                        class="input-editar-sensor"
                        type="number"
                        id="duracao"
                        name="duracao"
                        min="0"
                        step="0.01"
                        value="<?php echo htmlspecialchars($rotaEditar["duracao"]); ?>"
                        required
                    >

                    <br><br>

                    <label
                        for="data_criacao"
                        class="editar-sensor"
                    >
                        Data de criação
                    </label>

                    <br>

                    <input
                        class="input-editar-sensor"
                        type="date"
                        id="data_criacao"
                        name="data_criacao"
                        value="<?php echo htmlspecialchars($rotaEditar["data_criacao"]); ?>"
                        required
                    >

                    <br><br>

                    <label
                        for="trem_id"
                        class="editar-sensor"
                    >
                        Trem associado
                    </label>

                    <br>

                    <select
                        class="input-editar-sensor"
                        id="trem_id"
                        name="trem_id"
                        required
                    >

                        <option value="">
                            Selecione um trem
                        </option>

                        <?php foreach ($trens as $trem) { ?>

                            <option
                                value="<?php echo (int) $trem["id"]; ?>"
                                <?php
                                echo (
                                    (int) $rotaEditar["trem_id"]
                                    === (int) $trem["id"]
                                ) ? "selected" : "";
                                ?>
                            >

                                <?php echo htmlspecialchars($trem["modelo"]); ?>

                            </option>

                        <?php } ?>

                    </select>

                    <br><br>

                    <button
                        class="button-atualizar"
                        type="submit"
                        name="atualizar"
                    >
                        ATUALIZAR
                    </button>

                    <a
                        href="visualizar_rota.php"
                        class="button-cancelar"
                    >
                        CANCELAR
                    </a>

                </div>

            </form>

        </div>

        <hr>

    <?php } ?>

    <!-- TABELA DE ROTAS -->

    <div class="container_tabela_usuarios">

        <table class="tabela_usuarios table table-bordered">

            <thead>

                <tr>

                    <th scope="col">ID</th>

                    <th scope="col">Nome da rota</th>

                    <th scope="col">Descrição</th>

                    <th scope="col">Distância</th>

                    <th scope="col">Duração (horas)</th>

                    <th scope="col">Data de criação</th>

                    <th scope="col">Trem associado</th>

                    <th scope="col">Ações</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($rota = $resultado->fetch_assoc()) { ?>

                    <tr class="visualizar_usuario_gerente">

                        <th scope="row">

                            <?php echo (int) $rota["id"]; ?>

                        </th>

                        <td>

                            <?php echo htmlspecialchars($rota["nome"]); ?>

                        </td>

                        <td>

                            <?php echo htmlspecialchars($rota["descricao"] ?? ""); ?>

                        </td>

                        <td>

                            <?php echo htmlspecialchars($rota["distancia"]); ?>

                        </td>

                        <td>

                            <?php echo htmlspecialchars($rota["duracao"]); ?>

                        </td>

                        <td>

                            <?php
                            echo date(
                                "d/m/Y",
                                strtotime($rota["data_criacao"])
                            );
                            ?>

                        </td>

                        <td>

                            <?php echo htmlspecialchars($rota["modelo_trem"]); ?>

                        </td>

                        <td>

                            <a href="visualizar_rota.php?editar=<?php echo $rota['id']; ?>">
                                 EDITAR
                            </a>

                            <form
                                method="POST"
                                style="display:inline;"
                            >

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?php echo (int) $rota["id"]; ?>"
                                >

                                <button
                                    type="submit"
                                    name="excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir esta rota?');"
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
$resultadoTrens->free();

$conexao->close();

?>

</body>
</html>