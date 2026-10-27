<?php

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
        exit("Preencha todos os campos.");
    }

    $sql = "INSERT INTO rotas
            (nome, descricao, distancia, duracao, data_criacao, trem_id)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        exit("Erro ao conectar com o banco: " . $conexao->error);
    }

    $stmt->bind_param(
        'ssddsi',
        $nome,
        $descricao,
        $distancia,
        $duracao,
        $data_criacao,
        $trem_id
    );

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
    min="0"
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
    min="0"
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