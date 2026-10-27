<?php

session_start();

include_once("../infra/conexao.php");

// ==========================================
// VERIFICAR AUTENTICAÇÃO E PERMISSÕES
// ==========================================

$tipoUsuario = $_SESSION["tipo"] ?? "";

if (!in_array($tipoUsuario, ["gerente", "funcionario"], true)) {
    header("Location: login.php");
    exit();
}

// ==========================================
// VARIÁVEIS DE MENSAGEM
// ==========================================

$mensagemAlerta = "";

// ==========================================
// EXCLUIR TREM
// ==========================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["excluir"])
) {

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if (!$id || $id <= 0) {

        $mensagemAlerta = "ID do trem inválido!";

    } else {

        $stmt = $conexao->prepare(
            "DELETE FROM trens WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar exclusão: " . $conexao->error);
        }

        $stmt->bind_param("i", $id);

        try {

            if ($stmt->execute()) {

                if ($stmt->affected_rows > 0) {

                    $stmt->close();

                    header("Location: visualizar_trem.php?sucesso=excluir");
                    exit();

                } else {

                    $mensagemAlerta = "Trem não encontrado!";

                }

            } else {

                $mensagemAlerta = "Erro ao excluir o trem!";

            }

       } catch (mysqli_sql_exception $e) {

    if ($e->getCode() == 1451) {

        // Buscar as rotas vinculadas ao trem.

        $stmtRotas = $conexao->prepare(
            "SELECT id, nome
             FROM rotas
             WHERE trem_id = ?"
        );

        if (!$stmtRotas) {
            die("Erro ao consultar rotas: " . $conexao->error);
        }

        $stmtRotas->bind_param("i", $id);
        $stmtRotas->execute();

        $resultadoRotas = $stmtRotas->get_result();

        $rotasEncontradas = [];

        while ($rota = $resultadoRotas->fetch_assoc()) {

            $rotasEncontradas[] =
                "ID " . $rota["id"] . " - " . $rota["nome"];

        }

        $stmtRotas->close();

        if (!empty($rotasEncontradas)) {

            $mensagemAlerta =
                "Não foi possível excluir este trem!\n\n" .
                "Ele está vinculado às seguintes rotas:\n\n" .
                implode("\n", $rotasEncontradas);

        } else {

            $mensagemAlerta =
                "Não foi possível excluir este trem, pois existem registros vinculados a ele.";

        }

    } else {

        $mensagemAlerta = "Erro ao excluir o trem!";

    }

}

        $stmt->close();
    }
}

// ==========================================
// ATUALIZAR TREM
// ==========================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["atualizar"])
) {

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    $modelo = trim($_POST["modelo"] ?? "");
    $serie = trim($_POST["serie"] ?? "");
    $estado = $_POST["estado"] ?? "";

    $funcionarioId = filter_input(
        INPUT_POST,
        "funcionario_id",
        FILTER_VALIDATE_INT
    );

    // Validar campos obrigatórios.

    if (
        !$id ||
        $id <= 0 ||
        $modelo === "" ||
        $serie === "" ||
        !in_array($estado, ["operante", "inativo"], true) ||
        !$funcionarioId ||
        $funcionarioId <= 0
    ) {

        $mensagemAlerta = "Preencha todos os campos corretamente!";

    } elseif (
        strlen($modelo) > 100 ||
        strlen($serie) > 100
    ) {

        $mensagemAlerta = "O modelo e a série devem ter no máximo 100 caracteres!";

    } else {

        // Verificar se o funcionário existe.

        $stmtFuncionario = $conexao->prepare(
            "SELECT id FROM funcionarios WHERE id = ?"
        );

        if (!$stmtFuncionario) {
            die("Erro ao consultar funcionário: " . $conexao->error);
        }

        $stmtFuncionario->bind_param("i", $funcionarioId);
        $stmtFuncionario->execute();

        $resultadoFuncionario = $stmtFuncionario->get_result();

        if ($resultadoFuncionario->num_rows === 0) {

            $mensagemAlerta = "O funcionário selecionado não existe!";

        } else {

            // Atualizar os dados do trem.

            $stmt = $conexao->prepare(
                "UPDATE trens
                 SET modelo = ?,
                     serie = ?,
                     estado = ?,
                     funcionario_id = ?
                 WHERE id = ?"
            );

            if (!$stmt) {
                die("Erro ao preparar atualização: " . $conexao->error);
            }

            $stmt->bind_param(
                "sssii",
                $modelo,
                $serie,
                $estado,
                $funcionarioId,
                $id
            );

            if ($stmt->execute()) {

                $stmt->close();
                $stmtFuncionario->close();

                header("Location: visualizar_trem.php?sucesso=atualizar");
                exit();

            } else {

                $mensagemAlerta = "Erro ao atualizar o trem!";

            }

            $stmt->close();
        }

        $stmtFuncionario->close();
    }
}

// ==========================================
// MENSAGENS DE SUCESSO
// ==========================================

if (isset($_GET["sucesso"])) {

    if ($_GET["sucesso"] === "excluir") {

        $mensagemAlerta = "Trem excluído com sucesso!";

    } elseif ($_GET["sucesso"] === "atualizar") {

        $mensagemAlerta = "Trem atualizado com sucesso!";

    }
}

// ==========================================
// BUSCAR TREM PARA EDIÇÃO
// ==========================================

$tremEditar = null;

if (isset($_GET["editar"])) {

    $idEditar = filter_input(
        INPUT_GET,
        "editar",
        FILTER_VALIDATE_INT
    );

    if ($idEditar && $idEditar > 0) {

        $stmt = $conexao->prepare(
            "SELECT id, modelo, serie, estado, funcionario_id
             FROM trens
             WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar consulta: " . $conexao->error);
        }

        $stmt->bind_param("i", $idEditar);
        $stmt->execute();

        $resultadoEditar = $stmt->get_result();

        if ($resultadoEditar->num_rows === 1) {
            $tremEditar = $resultadoEditar->fetch_assoc();
        }

        $stmt->close();
    }
}

// ==========================================
// BUSCAR FUNCIONÁRIOS PARA O SELECT
// ==========================================

$sqlFuncionarios = "
    SELECT id, nome
    FROM funcionarios
    ORDER BY nome
";

$resultadoFuncionarios = $conexao->query($sqlFuncionarios);

if (!$resultadoFuncionarios) {
    die("Erro ao buscar funcionários: " . $conexao->error);
}

$funcionarios = [];

while ($funcionario = $resultadoFuncionarios->fetch_assoc()) {
    $funcionarios[] = $funcionario;
}

// ==========================================
// BUSCAR TRENS CADASTRADOS
// ==========================================

$sql = "
    SELECT
        trens.id,
        trens.modelo,
        trens.serie,
        trens.estado,
        trens.funcionario_id,
        funcionarios.nome AS nome_funcionario
    FROM trens
    INNER JOIN funcionarios
        ON trens.funcionario_id = funcionarios.id
    ORDER BY trens.modelo
";

$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Erro ao buscar trens: " . $conexao->error);
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

    <title>Visualização de trens</title>

    <link
        rel="icon"
        href="../assets/imgs/LogoDeTrain.png"
        type="image/x-icon"
    >

</head>

<body class="body_home">

    <?php if ($mensagemAlerta !== "") { ?>

        <script>
            alert(<?php echo json_encode($mensagemAlerta); ?>);

            // Remove o parâmetro de sucesso da URL para evitar
            // que o alerta apareça novamente ao apertar F5.

            <?php if (isset($_GET["sucesso"])) { ?>

                window.history.replaceState(
                    null,
                    "",
                    window.location.pathname
                );

            <?php } ?>
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

            <br>

        </div>

        <div class="lado-direito">

            <span>

                <?php

                echo htmlspecialchars(
                    $_SESSION["gerente_nome"]
                    ?? $_SESSION["funcionario_nome"]
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

        <!-- FORMULÁRIO DE EDIÇÃO -->

        <?php if ($tremEditar !== null) { ?>

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
                            value="<?php echo (int) $tremEditar["id"]; ?>"
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
                            maxlength="100"
                            required
                        >

                        <br><br>

                        <label for="serie" class="editar-sensor">
                            Série
                        </label>

                        <br>

                        <input
                            class="input-editar-sensor"
                            type="text"
                            id="serie"
                            name="serie"
                            value="<?php echo htmlspecialchars($tremEditar["serie"]); ?>"
                            maxlength="100"
                            required
                        >

                        <br><br>

                        <label for="estado" class="editar-sensor">
                            Estado
                        </label>

                        <br>

                        <select
                            class="input-editar-sensor"
                            id="estado"
                            name="estado"
                            required
                        >

                            <option
                                value="operante"
                                <?php echo $tremEditar["estado"] === "operante" ? "selected" : ""; ?>
                            >
                                Operante
                            </option>

                            <option
                                value="inativo"
                                <?php echo $tremEditar["estado"] === "inativo" ? "selected" : ""; ?>
                            >
                                Inativo
                            </option>

                        </select>

                        <br><br>

                        <label for="funcionario_id" class="editar-sensor">
                            Funcionário responsável
                        </label>

                        <br>

                        <select
                            class="input-editar-sensor"
                            id="funcionario_id"
                            name="funcionario_id"
                            required
                        >

                            <option value="">
                                Selecione um funcionário
                            </option>

                            <?php foreach ($funcionarios as $funcionario) { ?>

                                <option
                                    value="<?php echo (int) $funcionario["id"]; ?>"
                                    <?php
                                    echo (
                                        (int) $tremEditar["funcionario_id"]
                                        === (int) $funcionario["id"]
                                    ) ? "selected" : "";
                                    ?>
                                >

                                    <?php echo htmlspecialchars($funcionario["nome"]); ?>

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

        <!-- TABELA DE TRENS -->

        <div class="container_tabela_usuarios">

            <table class="tabela_usuarios table-bordered">

                <thead>

                    <tr>

                        <th scope="col">ID</th>

                        <th scope="col">Modelo</th>

                        <th scope="col">Série</th>

                        <th scope="col">Estado</th>

                        <th scope="col">Funcionário responsável</th>

                        <th scope="col">Ações</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($trem = $resultado->fetch_assoc()) { ?>

                        <tr class="visualizar_usuario_gerente">

                            <th scope="row">

                                <?php echo (int) $trem["id"]; ?>

                            </th>

                            <td>

                                <?php echo htmlspecialchars($trem["modelo"]); ?>

                            </td>

                            <td>

                                <?php echo htmlspecialchars($trem["serie"]); ?>

                            </td>

                            <td>

                                <?php echo htmlspecialchars($trem["estado"]); ?>

                            </td>

                            <td>

                                <?php echo htmlspecialchars($trem["nome_funcionario"]); ?>

                            </td>

                            <td>

                                <a href="visualizar_trem.php?editar=<?php echo (int) $trem["id"]; ?>">
                                    EDITAR
                                </a>

                                <form
                                    method="POST"
                                    style="display:inline;"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php echo (int) $trem["id"]; ?>"
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

    $resultado->free();
    $resultadoFuncionarios->free();

    $conexao->close();

    ?>

</body>

</html>