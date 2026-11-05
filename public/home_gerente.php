<?php
session_start();

include_once("../infra/conexao.php");
include_once("../infra/lembrar_login.php");

restaurarLogin($conexao);

if (
    !isset($_SESSION["gerente_id"]) ||
    !isset($_SESSION["tipo"]) ||
    $_SESSION["tipo"] !== "gerente"
) {
    header("Location: login.php");
    exit();
}

$nomeGerente = htmlspecialchars($_SESSION["gerente_nome"] ?? "Gerente");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel do Gerente</title>

    <link rel="stylesheet" href="../assets/style/style.css">
    <link rel="icon" href="../assets/imgs/PageIcon.png" type="image/x-icon">
</head>

<body class="body_home">

    <header class="hg-header">

        <div class="hg-brand">
            <img src="../assets/imgs/LogoDeTrain.png" alt="Logo DE-TRAIN">
            <a href="../public/home_gerente.php">DE-TRAIN</a>
        </div>


        <div class="hg-profile">
            <div class="hg-profile-info">
                <span>Bem-vindo(a),</span>
                <strong><?= $nomeGerente ?></strong>
            </div>


            <img src="../assets/imgs/LoginIcon.png" alt="Perfil">

            <a href="logout.php" class="hg-logout">Sair</a>
        </div>

    </header>


    <main class="hg-main">



        <section class="hg-dashboard">


            <aside class="hg-sidebar">

                
    <div class="hg-cep-card">

    <h3>Consulta de CEP</h3>

    <p>Digite um CEP para localizar um endereço.</p>

    <form id="formCEP">

        <input
            type="text"
            id="cep"
            placeholder="00000-000"
            maxlength="9"
            inputmode="numeric"
            required
        >

        <button type="submit">
            Consultar CEP
        </button>

    </form>

    <div id="resultadoCEP" class="hg-cep-resultado"></div>

    <p id="mensagemCEP" class="hg-cep-mensagem"></p>

    </div>

                <div class="hg-operations-card">
                    <h3>Área operacional</h3>
                    <p>Utilize os atalhos para administrar os registros do sistema.</p>

                    <a href="cadastro_trem.php">Cadastrar trem</a>
                    <a href="cadastro_rota.php">Cadastrar rota</a>
                    <a href="cadastro_sensor.php">Cadastrar sensor</a>
                    <a href="cadastro_funcionario.php">Cadastrar funcionário</a>
                </div>

            </aside>


            <section class="hg-map-section">

                <div class="hg-section-heading">
                    <div>
                        <h2>Mapa Ferroviário</h2>
                        <span>Visualização da malha ferroviária</span>
                    </div>

                    <span class="hg-map-tag">JOINVILLE - SC</span>
                </div>

                <div class="hg-map-container">

                    <img
                        src="../assets/imgs/sistema-metrô-joinvilense.webp"
                        alt="Mapa da malha ferroviária"
                        class="hg-map-image"
                    >

                </div>

               

            </section>



           <aside class="hg-alerts">

    <div class="hg-section-heading">
        <h2>Monitoramento Climático</h2>
    </div>

    <div class="hg-weather">

        <div class="hg-weather-location">
            <span class="hg-weather-pin">📍</span>
            <span>JOINVILLE - SC</span>
        </div>

        <div class="hg-weather-temperature">
            <span>Temperatura atual</span>
            <strong id="temperatura">-- °C</strong>
        </div>

        <div class="hg-weather-condition">
            <span>Condição climática</span>
            <strong id="condicao">Carregando...</strong>
        </div>

        <div class="hg-weather-update">
            <span id="atualizacao">Consultando clima...</span>
        </div>

    </div>

    <div class="hg-quick-access">

        <h3>Acessos rápidos</h3>

        <a href="visualizar_sensor.php">
            <span>Visualizar sensores</span>
            <span>→</span>
        </a>

        <a href="visualizar_trem.php">
            <span>Visualizar trens</span>
            <span>→</span>
        </a>

        <a href="visualizar_rota.php">
            <span>Visualizar rotas</span>
            <span>→</span>
        </a>

        <a href="visualizar_usuario.php">
            <span>Visualizar usuários</span>
            <span>→</span>
        </a>

        </section>

    </main>


  <footer class="hg-footer">
        <strong>Melhor ir DE-TRAIN!</strong>
        <span>SA-Ferrorama | Sistema de gerenciamento ferroviário</span>
    </footer>

    <!-- API DE MONITORAMENTO CLIMÁTICO -->
    <script>

        async function atualizarClima() {

            const latitude = -26.3044;
            const longitude = -48.8487;

            const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,weather_code&timezone=America%2FSao_Paulo`;

            try {

                const resposta = await fetch(url);

                if (!resposta.ok) {
                    throw new Error("Erro ao consultar a API");
                }

                const dados = await resposta.json();

                const temperatura = dados.current.temperature_2m;
                const codigo = dados.current.weather_code;

                const condicoes = {
                    0: "Céu limpo ☀️",
                    1: "Predominantemente limpo 🌤️",
                    2: "Parcialmente nublado ⛅",
                    3: "Nublado ☁️",
                    45: "Neblina 🌫️",
                    48: "Neblina com geada 🌫️",
                    51: "Garoa leve 🌧️",
                    53: "Garoa moderada 🌧️",
                    55: "Garoa intensa 🌧️",
                    61: "Chuva leve 🌧️",
                    63: "Chuva moderada 🌧️",
                    65: "Chuva intensa 🌧️",
                    80: "Pancadas de chuva 🌦️",
                    81: "Pancadas moderadas 🌧️",
                    82: "Pancadas fortes ⛈️",
                    95: "Tempestade ⛈️",
                    96: "Tempestade com granizo ⛈️",
                    99: "Tempestade com granizo forte ⛈️"
                };

                document.getElementById("temperatura").textContent =
                    temperatura.toFixed(1) + " °C";

                document.getElementById("condicao").textContent =
                    condicoes[codigo] || "Condição não identificada";

                document.getElementById("atualizacao").textContent =
                    "Última atualização: " +
                    new Date().toLocaleTimeString("pt-BR");

            } catch (erro) {

                document.getElementById("temperatura").textContent = "-- °C";

                document.getElementById("condicao").textContent =
                    "Clima indisponível";

                document.getElementById("atualizacao").textContent =
                    "Não foi possível consultar o clima.";

                console.error(erro);

            }

        }

        atualizarClima();

        setInterval(atualizarClima, 15 * 60 * 1000);

        document.getElementById("formCEP").addEventListener("submit", async function(event) {

    event.preventDefault();

    const cep = document.getElementById("cep").value.replace(/\D/g, "");

    const resultado = document.getElementById("resultadoCEP");
    const mensagem = document.getElementById("mensagemCEP");

    resultado.innerHTML = "";
    mensagem.textContent = "";

    if (cep.length !== 8) {
        mensagem.textContent = "Digite um CEP válido com 8 números.";
        return;
    }

    mensagem.textContent = "Consultando CEP...";

    try {

        const resposta = await fetch(`https://viacep.com.br/ws/${cep}/json/`);

        if (!resposta.ok) {
            throw new Error("Erro na consulta.");
        }

        const dados = await resposta.json();

        if (dados.erro) {
            mensagem.textContent = "CEP não encontrado.";
            return;
        }

        mensagem.textContent = "";

        const campos = [
            ["Rua", dados.logradouro],
            ["Bairro", dados.bairro],
            ["Cidade", dados.localidade],
            ["Estado", dados.uf]
        ];

        campos.forEach(([nome, valor]) => {

            const linha = document.createElement("p");
            const titulo = document.createElement("strong");

            titulo.textContent = nome + ": ";
            linha.appendChild(titulo);
            linha.appendChild(
                document.createTextNode(valor || "Não informado")
            );

            resultado.appendChild(linha);

        });

    } catch (erro) {

        mensagem.textContent = "Não foi possível consultar o CEP.";

        console.error(erro);

    }

});

    </script>

</body>
</html>