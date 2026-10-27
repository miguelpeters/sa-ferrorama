<?php

include '../infra/conexao.php';

$sucesso = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['numero_telefone'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (
        $nome === '' ||
        $email === '' ||
        $telefone === '' ||
        $senha === ''
    ) {
        $erro = "Preencha todos os campos.";
    } else {

        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO funcionarios
                (nome, email, numero_telefone, senha)
                VALUES (?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        if (!$stmt) {
            $erro = "Erro ao preparar cadastro: " . $conexao->error;
        } else {

            $stmt->bind_param(
                'ssss',
                $nome,
                $email,
                $telefone,
                $hash
            );

            if ($stmt->execute()) {
                $sucesso = "Funcionário cadastrado com sucesso!";
            } else {
                $erro = "Erro ao cadastrar funcionário.";
            }

            $stmt->close();
        }
    }
}

$conexao->close();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionário</title>

    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="icon" href="../assets/imgs/PageIcon.png" type="image/x-icon">
</head>

<body class="bodyCadastro">

    <?php if ($sucesso !== "") { ?>
        <script>
            alert(<?= json_encode($sucesso) ?>);
        </script>
    <?php } ?>

    <?php if ($erro !== "") { ?>
        <script>
            alert(<?= json_encode($erro) ?>);
        </script>
    <?php } ?>

    <div class="cadastro-detrain-topo">
        <a class="login-detrain-logo" href="home_gerente.php">DE-TRAIN</a>
        <img id="icon-login" src="../assets/imgs/LogoDeTrain.png">
    </div>

    <main>

        <div id="centrobloco">
            <div id="BlocoCadastro">

                <div class="Cadastro">

                    <h2 id="CadastroTitulo">Cadastrar Funcionário</h2>

                    <form id="FormsCadastro" method="POST">

                        <label for="cadastro-nome" id="CadastroLabel">Nome</label>
                        <input
                            type="text"
                            id="cadastro-nome"
                            name="nome"
                            class="CadastroInput"
                            placeholder="Nome do funcionário"
                            required
                        >
                        <br>

                        <label for="cadastro-email" id="CadastroLabel">Email</label>
                        <input
                            type="email"
                            id="cadastro-email"
                            name="email"
                            class="CadastroInput"
                            placeholder="Email do funcionário"
                            required
                        >
                        <br>

                        <label for="cadastro-telefone" id="CadastroLabel">Telefone</label>
                        <input
                            type="text"
                            id="cadastro-telefone"
                            name="numero_telefone"
                            class="CadastroInput"
                            placeholder="Telefone do funcionário"
                            required
                        >
                        <br>

                        <label for="cadastro-senha" id="CadastroLabel">Senha</label>
                        <input
                            type="password"
                            id="cadastro-senha"
                            name="senha"
                            class="CadastroInput"
                            placeholder="Insira a senha do funcionário"
                            required
                        >
                        <br>
                        <br>

                        <button type="submit" class="ButtonCadastro">
                            CADASTRAR FUNCIONÁRIO
                        </button>

                    </form>

                </div>

            </div>
        </div>

    </main>

</body>
</html>