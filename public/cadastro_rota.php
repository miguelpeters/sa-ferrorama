<?php

include '../infra/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
        exit("Informe o nome da rota.");
    }

    $sql = "INSERT INTO rotas (nome) VALUES (?)";
    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        exit("Erro ao conectar com o banco: " . $conexao->error);
    }

    $stmt->bind_param('s', $nome);

    if ($stmt->execute()) {
        echo "Rota cadastrada com sucesso! ";
        echo '<a href="cadastro_rota.php">Voltar</a>';
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
    exit;
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
    <a class="login-detrain-logo" href="home_gerente.php">DE-TRAIN</a>
    <img id="icon-login" src="../assets/imgs/LogoDeTrain.png">
  </div>
    <main>

        <div id="centrobloco">
            <div id="BlocoCadastro">

                <div class="Cadastro">

                    <h2 id="CadastroTitulo">Cadastrar Rota</h2>

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