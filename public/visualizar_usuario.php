<?php

session_start();

$tipoUsuario = $_SESSION["tipo"] ?? "";

if ($tipoUsuario !== "gerente") {
    header("Location: login.php");
    exit();
}

include_once("../infra/conexao.php");

$mensagem = "";
$mensagemSucesso = "";

// ==========================================
// DEFINIR TABELA PELO TIPO DE USUÁRIO
// ==========================================

function obterTabelaUsuario($tipo)
{
    if ($tipo === "Gerente") {
        return "gerentes";
    }

    if ($tipo === "Funcionário") {
        return "funcionarios";
    }

    return null;
}

// ==========================================
// EXCLUIR USUÁRIO
// ==========================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["excluir"])
) {

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $tipo = $_POST["tipo"] ?? "";

    $tabela = obterTabelaUsuario($tipo);

    if (!$id || $id <= 0 || $tabela === null) {

        $mensagem = "Dados inválidos para excluir o usuário.";

    } else {

        $stmt = $conexao->prepare(
            "DELETE FROM $tabela WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar exclusão: " . $conexao->error);
        }

        $stmt->bind_param("i", $id);

        try {

            $stmt->execute();

            if ($stmt->affected_rows > 0) {

                $stmt->close();

                header("Location: visualizar_usuario.php?sucesso=excluir");
                exit();

            } else {

                $mensagem = "Usuário não encontrado.";

            }

        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1451 && $tipo === "Funcionário") {

                // Verificar os trens associados ao funcionário.

                $stmtTrens = $conexao->prepare(
                    "SELECT id, modelo
                     FROM trens
                     WHERE funcionario_id = ?"
                );

                if (!$stmtTrens) {
                    die("Erro ao consultar trens: " . $conexao->error);
                }

                $stmtTrens->bind_param("i", $id);
                $stmtTrens->execute();

                $resultadoTrens = $stmtTrens->get_result();

                $trensEncontrados = [];

                while ($trem = $resultadoTrens->fetch_assoc()) {

                    $trensEncontrados[] =
                        "ID " . $trem["id"] .
                        " - " . $trem["modelo"];

                }

                $stmtTrens->close();

                if (!empty($trensEncontrados)) {

                    $mensagem =
                        "Não foi possível excluir este funcionário!\n\n" .
                        "Ele está associado aos seguintes trens:\n\n" .
                        implode("\n", $trensEncontrados);

                } else {

                    $mensagem =
                        "Não foi possível excluir o funcionário, pois existem outros registros vinculados a ele.";

                }

            } else {

                $mensagem =
                    "Não foi possível excluir este usuário, pois existem registros vinculados a ele.";

            }

        } finally {

            $stmt->close();

        }
    }
}

// ==========================================
// ATUALIZAR USUÁRIO
// ==========================================

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["atualizar"])
) {

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $tipo = $_POST["tipo"] ?? "";

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["numero_telefone"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $tabela = obterTabelaUsuario($tipo);

    if (
        !$id ||
        $id <= 0 ||
        $tabela === null ||
        $nome === "" ||
        $email === "" ||
        $telefone === ""
    ) {

        $mensagem = "Preencha todos os campos obrigatórios.";

    } elseif (
        strlen($nome) > 100 ||
        strlen($email) > 100 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($telefone) > 15
    ) {

        $mensagem = "Confira o nome, o e-mail e o telefone informados.";

    } else {

        // Verificar se o e-mail já pertence a outra conta.

        $stmtEmail = $conexao->prepare(
            "SELECT id FROM $tabela
             WHERE email = ? AND id <> ?"
        );

        if (!$stmtEmail) {
            die("Erro ao verificar e-mail: " . $conexao->error);
        }

        $stmtEmail->bind_param("si", $email, $id);
        $stmtEmail->execute();

        $resultadoEmail = $stmtEmail->get_result();

        if ($resultadoEmail->num_rows > 0) {

            $mensagem = "Este e-mail já está sendo utilizado por outra conta.";

            $stmtEmail->close();

        } else {

            $stmtEmail->close();

            if ($senha !== "") {

                // Atualizar os dados e a senha.

                $senhaHash = password_hash(
                    $senha,
                    PASSWORD_DEFAULT
                );

                $stmt = $conexao->prepare(
                    "UPDATE $tabela
                     SET nome = ?,
                         email = ?,
                         numero_telefone = ?,
                         senha = ?
                     WHERE id = ?"
                );

                if (!$stmt) {
                    die("Erro ao preparar atualização: " . $conexao->error);
                }

                $stmt->bind_param(
                    "ssssi",
                    $nome,
                    $email,
                    $telefone,
                    $senhaHash,
                    $id
                );

            } else {

                // Manter a senha atual.

                $stmt = $conexao->prepare(
                    "UPDATE $tabela
                     SET nome = ?,
                         email = ?,
                         numero_telefone = ?
                     WHERE id = ?"
                );

                if (!$stmt) {
                    die("Erro ao preparar atualização: " . $conexao->error);
                }

                $stmt->bind_param(
                    "sssi",
                    $nome,
                    $email,
                    $telefone,
                    $id
                );
            }

            try {

                $stmt->execute();
                $stmt->close();

                header("Location: visualizar_usuario.php?sucesso=atualizar");
                exit();

            } catch (mysqli_sql_exception $e) {

                $stmt->close();

                if ($e->getCode() == 1062) {

                    $mensagem = "Este e-mail já está sendo utilizado.";

                } else {

                    $mensagem = "Erro ao atualizar os dados do usuário.";

                }
            }
        }
    }
}

// ==========================================
// MENSAGENS DE SUCESSO
// ==========================================

if (isset($_GET["sucesso"])) {

    if ($_GET["sucesso"] === "excluir") {

        $mensagemSucesso = "Usuário excluído com sucesso!";

    } elseif ($_GET["sucesso"] === "atualizar") {

        $mensagemSucesso = "Usuário atualizado com sucesso!";

    }
}

// ==========================================
// BUSCAR USUÁRIO PARA EDIÇÃO
// ==========================================

$usuarioEditar = null;

if (isset($_GET["editar"], $_GET["tipo"])) {

    $idEditar = filter_input(
        INPUT_GET,
        "editar",
        FILTER_VALIDATE_INT
    );

    $tipoEditar = $_GET["tipo"];
    $tabela = obterTabelaUsuario($tipoEditar);

    if ($idEditar && $idEditar > 0 && $tabela !== null) {

        $stmt = $conexao->prepare(
            "SELECT id, nome, email, numero_telefone
             FROM $tabela
             WHERE id = ?"
        );

        if (!$stmt) {
            die("Erro ao preparar consulta: " . $conexao->error);
        }

        $stmt->bind_param("i", $idEditar);
        $stmt->execute();

        $resultadoEditar = $stmt->get_result();

        if ($resultadoEditar->num_rows === 1) {

            $usuarioEditar = $resultadoEditar->fetch_assoc();
            $usuarioEditar["tipo"] = $tipoEditar;

        }

        $stmt->close();
    }
}

// ==========================================
// LISTAR GERENTES E FUNCIONÁRIOS
// ==========================================

$sql = "
    SELECT
        id,
        nome,
        email,
        numero_telefone,
        'Gerente' AS tipo
    FROM gerentes

    UNION ALL

    SELECT
        id,
        nome,
        email,
        numero_telefone,
        'Funcionário' AS tipo
    FROM funcionarios

    ORDER BY nome
";

$resultado = $conexao->query($sql);

if (!$resultado) {
    die("Erro ao buscar usuários: " . $conexao->error);
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

    <title>Visualização de usuários</title>

    <link
        rel="icon"
        href="../assets/imgs/LogoDeTrain.png"
        type="image/x-icon"
    >

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
            >

            <br>

        </div>

        <div class="lado-direito">

            <span>
                <?php
                echo htmlspecialchars(
                    $_SESSION["gerente_nome"] ?? "GERENTE"
                );
                ?>
                - GERENTE
            </span>

            <img
                src="../assets/imgs/LoginIcon.png"
                class="perfil"
            >

        </div>

    </header>

    <main>

        <?php if ($usuarioEditar !== null) { ?>

            <div class="container_editar_usuario">

                <div class="texto-editar-usuario">

                    <h2>
                        Editar <?php echo htmlspecialchars($usuarioEditar["tipo"]); ?>
                    </h2>

                </div>

                <form method="POST">

                    <div class="form-editar-usuario">

                        <input
                            type="hidden"
                            name="id"
                            value="<?php echo (int) $usuarioEditar["id"]; ?>"
                        >

                        <input
                            type="hidden"
                            name="tipo"
                            value="<?php echo htmlspecialchars($usuarioEditar["tipo"]); ?>"
                        >

                        <label for="nome" class="editar-usuario">
                            Nome
                        </label>

                        <br>

                        <input
                            class="input-editar-usuario"
                            type="text"
                            id="nome"
                            name="nome"
                            maxlength="100"
                            value="<?php echo htmlspecialchars($usuarioEditar["nome"]); ?>"
                            required
                        >

                        <br><br>

                        <label for="email" class="editar-usuario">
                            Email
                        </label>

                        <br>

                        <input
                            class="input-editar-usuario"
                            type="email"
                            id="email"
                            name="email"
                            maxlength="100"
                            value="<?php echo htmlspecialchars($usuarioEditar["email"]); ?>"
                            required
                        >

                        <br><br>

                        <label for="numero_telefone" class="editar-usuario">
                            Telefone
                        </label>

                        <br>

                        <input
                            class="input-editar-usuario"
                            type="text"
                            id="numero_telefone"
                            name="numero_telefone"
                            maxlength="15"
                            value="<?php echo htmlspecialchars($usuarioEditar["numero_telefone"]); ?>"
                            required
                        >

                        <br><br>

                        <label for="senha" class="editar-usuario">
                            Nova senha
                        </label>

                        <br>

                        <input
                            class="input-editar-usuario"
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Deixe em branco para manter a senha atual"
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
                            href="visualizar_usuario.php"
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

                        <th scope="col">Nome</th>

                        <th scope="col">Email</th>

                        <th scope="col">Telefone</th>

                        <th scope="col">Tipo</th>

                        <th scope="col">Ações</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($pessoa = $resultado->fetch_assoc()) { ?>

                        <tr class="visualizar_usuario_gerente">

                            <th scope="row">

                                <?php echo (int) $pessoa["id"]; ?>

                            </th>

                            <td>

                                <?php echo htmlspecialchars($pessoa["nome"]); ?>

                            </td>

                            <td>

                                <?php echo htmlspecialchars($pessoa["email"]); ?>

                            </td>

                            <td>

                                <?php echo htmlspecialchars($pessoa["numero_telefone"]); ?>

                            </td>

                            <td>

                                <?php echo htmlspecialchars($pessoa["tipo"]); ?>

                            </td>

                            <td>

                                <a
                                    href="visualizar_usuario.php?editar=<?php echo (int) $pessoa["id"]; ?>&tipo=<?php echo urlencode($pessoa["tipo"]); ?>"
                                >
                                    EDITAR
                                </a>

                                <form
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php echo (int) $pessoa["id"]; ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="tipo"
                                        value="<?php echo htmlspecialchars($pessoa["tipo"]); ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="excluir"
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