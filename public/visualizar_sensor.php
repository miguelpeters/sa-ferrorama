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

    $id = $_POST["id"];
    $tipo = $_POST["tipo"];

    if ($tipo == "Gerente") {
        $tabela = "gerentes";
    } elseif ($tipo == "Funcionário") {
        $tabela = "funcionarios";
    } elseif ($tipo == "Usuário") {
        $tabela = "usuarios";
    } else {
        die("Tipo de usuário inválido.");
    }

    $sql = "DELETE FROM $tabela WHERE id = ?";

    $stmt = $conexao->prepare($sql);

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["atualizar"])) {

    $id = $_POST["id"];
    $tipo = $_POST["tipo"];

    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["numero_telefone"];
    $senha = $_POST["senha"];

    if ($tipo == "Gerente") {
        $tabela = "gerentes";
    } elseif ($tipo == "Funcionário") {
        $tabela = "funcionarios";
    } elseif ($tipo == "Usuário") {
        $tabela = "usuarios";
    } else {
        die("Tipo de usuário inválido.");
    }


    $sql = "UPDATE $tabela
            SET nome = ?, email = ?, numero_telefone = ?, senha = ?
            WHERE id = ?";


    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar atualização: " . $conexao->error);
    }


    $stmt->bind_param(
        "ssssi",
        $tipo,
        $id_rota,
        $id,
    );


    if (!$stmt->execute()) {
        die("Erro ao atualizar sensor: " . $stmt->error);
    }


    $stmt->close();

    header("Location: visualizar_sensor.php");
    exit();
}

$sensorEditar = null;

if (isset($_GET["editar"]) && isset($_GET["tipo"])) {

    $id = $_GET["editar"];
    $tipo = $_GET["tipo"];


    if ($tipo == "Gerente") {
        $tabela = "gerentes";
    } elseif ($tipo == "Funcionário") {
        $tabela = "funcionarios";
    } elseif ($tipo == "Usuário") {
        $tabela = "usuarios";
    } else {
        die("Tipo de usuário inválido.");
    }


    $sql = "SELECT tipo, rota_id, id
            FROM $tabela
            WHERE id = ?";


    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        die("Erro ao preparar consulta: " . $conexao->error);
    }


    $stmt->bind_param("i", $id);

    $stmt->execute();

    $resultadoEditar = $stmt->get_result();


    if ($resultadoEditar->num_rows == 1) {

        $sensorEditar = $resultadoEditar->fetch_assoc();
        $sensorEditar["tipo"] = $tipo;
    }


    $stmt->close();
}

?>


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/style/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <title>Visualização de sensores</title>

  <link rel="icon" href="../assets/imgs/LogoDeTrain.png" type="image/x-icon">
</head>

<body class="body_home">

  <header class="cabecalho">

    <div class="lado-esquerdo">
        <button class="buttonRetornar" onclick="history.back()">DE-TRAIN</button>
        <img id="icon" src="../assets/imgs/LogoDeTrain.png" class="logo">
        <br>
    </div>

    <div class="lado-direito">
      <span>FULANO DE TAL - FUNCIONÁRIO</span>
      <img src="../assets/imgs/LoginIcon.png" class="perfil">
    </div>

  </header>



  <main>

  
   <?php if ($sensorEditar != null) { ?>

        <div class="container_editar_usuario">

        <div class="texto-editar-usuario">
            <h2>Editar <?php echo $sensorEditar["tipo"]; ?></h2>
        </div>
            <form method="POST">
                <div class="form-editar-usuario">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $sensorEditar["id"]; ?>"
                >

                <input
                    type="hidden"
                    name="tipo"
                    value="<?php echo htmlspecialchars($sensorEditar["tipo"]); ?>"
                >

                <label for="nome" class="editar-usuario">
                    Nome
                </label>

                <br>

                <input class="input-editar-usuario"
                    type="text"
                    id="nome"
                    name="nome"
                    value="<?php echo htmlspecialchars($sensorEditar["nome"]); ?>"
                    required
                >

                <br><br>

                <label for="email" class="editar-usuario">
                    Email
                </label>

                <br>

                <input class="input-editar-usuario"
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($sensorEditar["email"]); ?>"
                    required
                >

                <br><br>

                <label for="numero_telefone" class="editar-usuario">
                    Telefone
                </label>

                <br>

                <input class="input-editar-usuario"
                    type="text"
                    id="numero_telefone"
                    name="numero_telefone"
                    value="<?php echo htmlspecialchars($sensorEditar["numero_telefone"]); ?>"
                    required
                >

                <br><br>

                <label for="senha" class="editar-usuario">
                    Senha
                </label>

                <br>

                <input class="input-editar-usuario"
                    type="text"
                    id="senha"
                    name="senha"
                    value="<?php echo htmlspecialchars($sensorEditar["senha"]); ?>"
                    required
                >

                <br><br>

                <button class="button-atualizar"
                    type="submit"
                    name="atualizar"
                >
                    ATUALIZAR
                </button>


                <a href="visualizar_usuario.php" class="button-cancelar">
                    CANCELAR
                </a>


            </form>

        </div>
        </div>

        <hr>

    <?php } ?>

    <div class="container_tabela_usuarios">


        <table class="tabela_usuarios table-bordered">


            <thead>

                <tr>

                    <th scope="col">
                        #
                    </th>

                    <th scope="col">
                        Nome
                    </th>

                    <th scope="col">
                        Email
                    </th>

                    <th scope="col">
                        Telefone
                    </th>

                    <th scope="col">
                        Tipo
                    </th>

                    <th scope="col">
                        Ações
                    </th>

                </tr>

            </thead>


            <tbody>


                <?php

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

                    UNION ALL

                    SELECT
                        id,
                        nome,
                        email,
                        numero_telefone,
                        'Usuário' AS tipo
                    FROM usuarios

                    ORDER BY nome

                ";


                $resultado = $conexao->query($sql);


                if (!$resultado) {

                    die(
                        "Erro ao buscar usuários: "
                        . $conexao->error
                    );

                }

                while ($pessoa = $resultado->fetch_assoc()) {

                ?>

                    <tr class="visualizar_usuario_gerente">

                        <th scope="row">

                            <?php

                            echo htmlspecialchars(
                                $pessoa["id"]
                            );

                            ?>

                        </th>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $pessoa["nome"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $pessoa["email"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $pessoa["numero_telefone"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $pessoa["tipo"]
                            );

                            ?>

                        </td>

                        <td>

                            <a
                                href="visualizar_usuario.php?editar=<?php echo $pessoa["id"]; ?>&tipo=<?php echo urlencode($pessoa["tipo"]); ?>"
                            >
                            
                                EDITAR
                            </a>

                            <form
                                method="POST"
                                style="display:inline;"
                            >


                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?php echo $pessoa["id"]; ?>"
                                >


                                <input
                                    type="hidden"
                                    name="tipo"
                                    value="<?php echo htmlspecialchars($pessoa["tipo"]); ?>"
                                >


                                <button
                                    type="submit"
                                    name="excluir"
                                    onclick="return confirm('Tem certeza que deseja excluir este usuário?');"
                                >

                                    EXCLUIR

                                </button>


                            </form>


                        </td>


                    </tr>


                <?php

                }

                ?>


            </tbody>


        </table>


    </div>


</main>


<?php

$conexao->close();

?>

</body>

</html>

   