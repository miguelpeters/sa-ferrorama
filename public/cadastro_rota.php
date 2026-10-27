
<?php

session_start();

$tipoUsuario = $_SESSION['tipo'] ?? '';

if ($tipoUsuario === 'funcionario') {
    $paginaHome = 'home_funcionario.php';
} elseif ($tipoUsuario === 'gerente') {
    $paginaHome = 'home_gerente.php';
} else {
    header('Location: login.php');
    exit;
}

include '../infra/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $distancia = $_POST['distancia'] ?? '';
    $duracao = $_POST['duracao'] ?? '';
    $data_criacao = $_POST['data_criacao'] ?? '';
    $trem_id = $_POST['trem_id'] ?? '';

    if (
        $nome === '' ||
        $descricao === '' ||
        $distancia === '' ||
        $duracao === '' ||
        $data_criacao === '' ||
        $trem_id === ''
    ) {
        $erro = "Preencha todos os campos.";
    } elseif (
        !is_numeric($distancia) || (float)$distancia <= 0 ||
        !is_numeric($duracao) || (float)$duracao <= 0
    ) {
        $erro = "Informe uma distância e duração válidas.";
    } elseif (!ctype_digit((string)$trem_id) || (int)$trem_id < 1) {
        $erro = "Informe um ID de trem válido.";
    } else {

        $trem_id = (int)$trem_id;

        $sql = "INSERT INTO rotas
                (nome, descricao, distancia, duracao, data_criacao, trem_id)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        if (!$stmt) {
            $erro = "Erro ao preparar cadastro: " . $conexao->error;
        } else {

            $stmt->bind_param(
                "ssddsi",
                $nome,
                $descricao,
                $distancia,
                $duracao,
                $data_criacao,
                $trem_id
            );

            if ($stmt->execute()) {
                $sucesso = "Rota cadastrada com sucesso! ID: "
                    . $conexao->insert_id;
            } else {
                $erro = "Erro ao cadastrar rota: " . $stmt->error;
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
    <title>Cadastro de Rota</title>

    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="icon" href="../assets/imgs/PageIcon.png" type="image/x-icon">
</head>

<body class="bodyCadastro">

    <div class="cadastro-detrain-topo">
        <a class="login-detrain-logo" href="<?= htmlspecialchars($paginaHome) ?>">DE-TRAIN</a>
        <img id="icon-login" src="../assets/imgs/LogoDeTrain.png">
    </div>

    <main>
        <div id="centrobloco">
            <div id="BlocoCadastro">
                <div class="Cadastro">

                    <h2 id="CadastroTitulo">Cadastrar Rota</h2>

                    <?php if (isset($sucesso)) { ?>
                        <p><?= htmlspecialchars($sucesso) ?></p>
                    <?php } ?>

                    <?php if (isset($erro)) { ?>
                        <p><?= htmlspecialchars($erro) ?></p>
                    <?php } ?>

                    <form id="FormsCadastro" method="POST">

                        <label for="cadastro-nome" id="CadastroLabel">Nome</label>
                        <input
                            type="text"
                            id="cadastro-nome"
                            name="nome"
                            class="CadastroInput"
                            placeholder="Nome da rota"
                            required
                        >
                        <br><br>

                        <label for="cadastro-descricao" id="CadastroLabel">Descrição</label>
                        <input
                            type="text"
                            id="cadastro-descricao"
                            name="descricao"
                            class="CadastroInput"
                            placeholder="Descrição da rota"
                            required
                        >
                        <br><br>

                        <label for="cadastro-distancia" id="CadastroLabel">Distância (km)</label>
                        <input
                            type="number"
                            id="cadastro-distancia"
                            name="distancia"
                            class="CadastroInput"
                            placeholder="Distância da rota"
                            step="0.01"
                            min="0.01"
                            required
                        >
                        <br><br>

                        <label for="cadastro-duracao" id="CadastroLabel">Duração (horas)</label>
                        <input
                            type="number"
                            id="cadastro-duracao"
                            name="duracao"
                            class="CadastroInput"
                            placeholder="Duração da rota"
                            step="0.01"
                            min="0.01"
                            required
                        >
                        <br><br>

                        <label for="cadastro-data" id="CadastroLabel">Data de criação</label>
                        <input
                            type="date"
                            id="cadastro-data"
                            name="data_criacao"
                            class="CadastroInput"
                            required
                        >
                        <br><br>

                        <label for="cadastro-trem" id="CadastroLabel">ID do trem</label>
                        <input
                            type="number"
                            id="cadastro-trem"
                            name="trem_id"
                            class="CadastroInput"
                            placeholder="ID do trem associado"
                            min="1"
                            required
                        >
                        <br><br>

                        <button type="submit" class="ButtonCadastro">
                            CADASTRAR ROTA
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </main>

</body>
</html>