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

$resultadoFuncionarios = $conexao->query("SELECT id, nome FROM funcionarios ORDER BY nome");
$resultadoTrens = $conexao->query("SELECT id, modelo FROM trens ORDER BY modelo");
$resultadoRotas = $conexao->query("SELECT id, nome FROM rotas ORDER BY nome");
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
            <a href="../public/home_gerente.php">DE-TRAIN - Perfil Adminstrativo</a>
        </div>


        <div class="hg-profile">
            <div class="hg-profile-info">
                <span>Bem-vindo(a),</span>
                <strong><?= $nomeGerente ?></strong>
            </div>

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
                    <h3>Cadastros</h3>
                    <p>Utilize os atalhos para cadastrar novos registros no sistema.</p>

                    <a href="cadastro_trem.php" id="abrirModalTrem">Cadastrar trem</a>
                    <a href="cadastro_rota.php" id="abrirModalRota">Cadastrar rota</a>
                    <a href="cadastro_sensor.php" id="abrirModalSensor">Cadastrar sensor</a>
                    <a href="cadastro_funcionario.php" id="abrirModalFuncionario">Cadastrar funcionário</a>
                </div>

            </aside>


            <section class="hg-map-section">

                <div class="hg-section-heading">
                    <div>
                    </div>

        
                </div>

                    <div class="hg-map-container">

                            <iframe
                                id="mapaWindy"
                                class="hg-map-iframe"
                                src="https://embed.windy.com/embed2.html?lat=-26.30&lon=-48.85&detailLat=-26.30&detailLon=-48.85&zoom=8&level=surface&overlay=wind&product=ecmwf&menu=true&message=true&marker=true&calendar=now&type=map&location=coordinates&metricWind=km%2Fh&metricTemp=%C2%B0C&radarRange=-1"
                                title="Mapa de vento e chuva"
                                loading="lazy">
                            </iframe>

                    </div>

                </div>

               

            </section>



           <aside class="hg-alerts">

    <div class="hg-section-heading">
        <h2>Monitoramento Climático</h2>
    </div>

    <div class="hg-weather">

        <div class="hg-weather-location">
            <span class="hg-weather-pin"></span>
            <span class="hg-map-tag" id="localMapa">JOINVILLE - SC</span>
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

        </div> 

            </aside> 

            </section> 

            </main>

  <footer class="hg-footer">
        <strong>Melhor ir DE-TRAIN!</strong>
        <span><a href="../public/landing_page.php" class="link-limpo">→ Conheça o projeto!</a></span>
    </footer>

    <dialog id="modalTrem" class="modal-trem">
    <div class="modal-conteudo">

        <button type="button" id="fecharModalTrem" class="modal-fechar" aria-label="Fechar">&times;</button>

        <div id="BlocoCadastro">
            <div class="Cadastro">

                <h2 id="CadastroTitulo">Cadastrar Trem</h2>

                <p id="mensagemTrem" role="status"></p>

                <form id="FormsCadastro" method="POST">

                    <label for="cadastro-modelo" id="CadastroLabel">Modelo</label>
                    <input type="text" id="cadastro-modelo" name="modelo"
                           class="CadastroInput" placeholder="Modelo do trem" required>
                    <br><br>

                    <label for="cadastro-serie" id="CadastroLabel">Série</label>
                    <input type="text" id="cadastro-serie" name="serie"
                           class="CadastroInput" placeholder="Série do trem" required>
                    <br><br>

                    <label for="estado" id="CadastroLabel">Estado</label>
                    <select id="estado" name="estado" class="CadastroInput" required>
                        <option value="">Selecione o estado</option>
                        <option value="operante">Operante</option>
                        <option value="inativo">Inativo</option>
                    </select>
                    <br><br>

                    <label for="funcionario_id" id="CadastroLabel">Funcionário</label>
                    <select id="funcionario_id" name="funcionario_id" class="CadastroInput" required>
                        <option value="">Selecione um funcionário</option>
                        <?php if ($resultadoFuncionarios): ?>
                            <?php while ($func = $resultadoFuncionarios->fetch_assoc()): ?>
                                <option value="<?= (int) $func['id'] ?>">
                                    <?= htmlspecialchars($func['id'] . ' - ' . $func['nome']) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>

                    <?php if ($resultadoFuncionarios && $resultadoFuncionarios->num_rows === 0): ?>
                        <p>Nenhum funcionário cadastrado. Cadastre um funcionário primeiro.</p>
                    <?php endif; ?>
                    <br><br>

                    <button type="submit" class="ButtonCadastro">CADASTRAR TREM</button>

                </form>
            </div>
        </div>

    </div>
</dialog>

<dialog id="modalRota" class="modal-trem modal-rota">
    <div class="modal-conteudo">

        <button type="button" id="fecharModalRota" class="modal-fechar" aria-label="Fechar">&times;</button>

        <div id="BlocoCadastro">
            <div class="Cadastro">

                <h2 id="CadastroTitulo">Cadastrar Rota</h2>

                <p id="mensagemRota" role="status"></p>

                <form id="FormsCadastro" method="POST">

                    <label for="cadastro-nome" id="CadastroLabel">Nome</label>
                    <input type="text" id="cadastro-nome" name="nome"
                           class="CadastroInput" placeholder="Nome da rota" required>
                    <br><br>

                    <label for="cadastro-descricao" id="CadastroLabel">Descrição</label>
                    <input type="text" id="cadastro-descricao" name="descricao"
                           class="CadastroInput" placeholder="Descrição da rota" required>
                    <br><br>

                    <label for="cadastro-distancia" id="CadastroLabel">Distância (km)</label>
                    <input type="number" id="cadastro-distancia" name="distancia"
                           class="CadastroInput" placeholder="Distância da rota"
                           step="0.01" min="0.01" required>
                    <br><br>

                    <label for="cadastro-duracao" id="CadastroLabel">Duração (horas)</label>
                    <input type="number" id="cadastro-duracao" name="duracao"
                           class="CadastroInput" placeholder="Duração da rota"
                           step="0.01" min="0.01" required>
                    <br><br>

                    <label for="cadastro-data" id="CadastroLabel">Data de criação</label>
                    <input type="date" id="cadastro-data" name="data_criacao"
                           class="CadastroInput" required>
                    <br><br>

                    <label for="cadastro-trem" id="CadastroLabel">Trem</label>
                    <select id="cadastro-trem" name="trem_id" class="CadastroInput" required>
                        <option value="">Selecione um trem</option>
                        <?php if ($resultadoTrens): ?>
                            <?php while ($trem = $resultadoTrens->fetch_assoc()): ?>
                                <option value="<?= (int) $trem['id'] ?>">
                                    <?= htmlspecialchars($trem['id'] . ' - ' . $trem['modelo']) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>

                    <?php if ($resultadoTrens && $resultadoTrens->num_rows === 0): ?>
                        <p>Nenhum trem cadastrado. Cadastre um trem primeiro.</p>
                    <?php endif; ?>
                    <br><br>

                    <button type="submit" class="ButtonCadastro">CADASTRAR ROTA</button>

                </form>
            </div>
        </div>

    </div>
</dialog>

<dialog id="modalSensor" class="modal-trem modal-sensor">
    <div class="modal-conteudo">

        <button type="button" id="fecharModalSensor" class="modal-fechar" aria-label="Fechar">&times;</button>

        <div id="BlocoCadastro">
            <div class="Cadastro">

                <h2 id="CadastroTitulo">Cadastrar Sensor</h2>

                <p id="mensagemSensor" role="status"></p>

                <form id="FormsCadastro" method="POST">

                    <label class="label-sensor" for="cadastro-sensor-nome">Nome</label>
                    <input type="text" id="cadastro-sensor-nome" name="nome"
                           class="CadastroInput" placeholder="Nome do sensor"
                           maxlength="50" required>
                    <br>

                    <label class="label-sensor" for="cadastro-sensor-tipo">Tipo</label>
                    <input type="text" id="cadastro-sensor-tipo" name="tipo"
                           class="CadastroInput" placeholder="Tipo do sensor"
                           maxlength="50" required>
                    <br>

                    <label class="label-sensor" for="cadastro-rota">Rota</label>
                    <select id="cadastro-rota" name="rota_id" class="CadastroInput" required>
                        <option value="">Selecione uma rota</option>
                        <?php if ($resultadoRotas): ?>
                            <?php while ($rota = $resultadoRotas->fetch_assoc()): ?>
                                <option value="<?= (int) $rota['id'] ?>">
                                    <?= htmlspecialchars($rota['nome']) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>

                    <?php if ($resultadoRotas && $resultadoRotas->num_rows === 0): ?>
                        <p>Nenhuma rota cadastrada. Cadastre uma rota primeiro.</p>
                    <?php endif; ?>
                    <br><br>

                    <button type="submit" class="ButtonCadastro">CADASTRAR SENSOR</button>

                </form>
            </div>
        </div>

    </div>
</dialog>

<dialog id="modalFuncionario" class="modal-trem modal-funcionario">
    <div class="modal-conteudo">

        <button type="button" id="fecharModalFuncionario" class="modal-fechar" aria-label="Fechar">&times;</button>

        <div id="BlocoCadastro">
            <div class="Cadastro">

                <h2 id="CadastroTitulo">Cadastrar Funcionário</h2>

                <p id="mensagemFuncionario" role="status"></p>

                <form id="FormsCadastro" method="POST" autocomplete="off">

                    <label for="cadastro-func-nome" id="CadastroLabel">Nome</label>
                    <input type="text" id="cadastro-func-nome" name="nome"
                           class="CadastroInput" placeholder="Nome do funcionário" required>
                    <br>

                    <label for="cadastro-func-email" id="CadastroLabel">Email</label>
                    <input type="email" id="cadastro-func-email" name="email"
                           class="CadastroInput" placeholder="Email do funcionário" required>
                    <br>

                    <label for="cadastro-func-telefone" id="CadastroLabel">Telefone</label>
                    <input type="text" id="cadastro-func-telefone" name="numero_telefone"
                           class="CadastroInput" placeholder="Telefone do funcionário" required>
                    <br>

                    <label for="cadastro-func-senha" id="CadastroLabel">Senha</label>
                    <input type="password" id="cadastro-func-senha" name="senha"
                           class="CadastroInput" placeholder="Insira a senha do funcionário"
                           autocomplete="new-password" required>
                    <br><br>

                    <button type="submit" class="ButtonCadastro">CADASTRAR FUNCIONÁRIO</button>

                </form>
            </div>
        </div>

    </div>
</dialog>

    <script>

    let localAtual = { lat: -26.3044, lon: -48.8487 };

    async function atualizarClima(latitude = localAtual.lat, longitude = localAtual.lon) {

        const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,weather_code&timezone=auto`;

        try {

            const resposta = await fetch(url);

            if (!resposta.ok) {
                throw new Error("Erro ao consultar a API");
            }

            const dados = await resposta.json();

            const temperatura = dados.current.temperature_2m;
            const codigo = dados.current.weather_code;

            const condicoes = {
                0: "Céu limpo",
                1: "Predominantemente limpo",
                2: "Parcialmente nublado",
                3: "Nublado",
                45: "Neblina",
                48: "Neblina com geada",
                51: "Garoa leve",
                53: "Garoa moderada",
                55: "Garoa intensa",
                61: "Chuva leve",
                63: "Chuva moderada",
                65: "Chuva intensa",
                80: "Pancadas de chuva",
                81: "Pancadas moderadas",
                82: "Pancadas fortes",
                95: "Tempestade",
                96: "Tempestade com granizo",
                99: "Tempestade com granizo forte"
            };

            document.getElementById("temperatura").textContent =
                temperatura.toFixed(1) + " °C";

            document.getElementById("condicao").textContent =
                condicoes[codigo] || "Condição não identificada";

            document.getElementById("atualizacao").textContent =
                "Última atualização: " + new Date().toLocaleTimeString("pt-BR");

        } catch (erro) {

            document.getElementById("temperatura").textContent = "-- °C";
            document.getElementById("condicao").textContent = "Clima indisponível";
            document.getElementById("atualizacao").textContent = "Não foi possível consultar o clima.";

            console.error(erro);
        }
    }

    async function mudarLocal(cidade, uf) {

        try {

            const q = encodeURIComponent(`${cidade}, ${uf}, Brasil`);
            const resposta = await fetch(`https://nominatim.openstreetmap.org/search?q=${q}&format=json&limit=1`);
            const lista = await resposta.json();

            if (!lista.length) return;

            const lat = parseFloat(lista[0].lat);
            const lon = parseFloat(lista[0].lon);

            localAtual = { lat, lon };

            document.getElementById("localMapa").textContent =
                `${cidade} - ${uf}`.toUpperCase();

            document.getElementById("mapaWindy").src =
                `https://embed.windy.com/embed2.html?lat=${lat}&lon=${lon}&detailLat=${lat}&detailLon=${lon}&zoom=8&level=surface&overlay=wind&product=ecmwf&menu=true&message=true&marker=true&calendar=now&type=map&location=coordinates&metricWind=km%2Fh&metricTemp=%C2%B0C&radarRange=-1`;

            atualizarClima(lat, lon);

        } catch (erro) {
            console.error(erro);
        }
    }

    atualizarClima();

    setInterval(() => atualizarClima(), 15 * 60 * 1000);


    document.getElementById("formCEP").addEventListener("submit", async function (event) {

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
                linha.appendChild(document.createTextNode(valor || "Não informado"));

                resultado.appendChild(linha);
            });

            mudarLocal(dados.localidade, dados.uf);

        } catch (erro) {

            mensagem.textContent = "Não foi possível consultar o CEP.";
            console.error(erro);
        }
    });

    const modalTrem = document.getElementById("modalTrem");
const formTrem = modalTrem.querySelector("form");
const mensagemTrem = document.getElementById("mensagemTrem");

document.getElementById("abrirModalTrem").addEventListener("click", function (event) {
    event.preventDefault();
    mensagemTrem.textContent = "";
    modalTrem.showModal();
});

document.getElementById("fecharModalTrem").addEventListener("click", () => modalTrem.close());

modalTrem.addEventListener("click", function (event) {
    if (event.target === modalTrem) modalTrem.close();
});

formTrem.addEventListener("submit", async function (event) {
    event.preventDefault();

    mensagemTrem.textContent = "Cadastrando...";

    try {
        const resposta = await fetch("processa_trem.php", {
            method: "POST",
            body: new FormData(formTrem)
        });

        const dados = await resposta.json();

        mensagemTrem.textContent = dados.mensagem;

        if (dados.ok) {

            // coloca o trem novo na lista do modal de rota
            const selectTrem = document.getElementById("cadastro-trem");
            selectTrem.add(new Option(dados.id + " - " + formTrem.modelo.value, dados.id));

            formTrem.reset();
        }
    } catch (erro) {
        mensagemTrem.textContent = "Não foi possível cadastrar o trem.";
        console.error(erro);
    }
});




const modalRota = document.getElementById("modalRota");
const formRota = modalRota.querySelector("form");
const mensagemRota = document.getElementById("mensagemRota");

document.getElementById("abrirModalRota").addEventListener("click", function (event) {
    event.preventDefault();
    mensagemRota.textContent = "";
    modalRota.showModal();
});

document.getElementById("fecharModalRota").addEventListener("click", () => modalRota.close());

modalRota.addEventListener("click", function (event) {
    if (event.target === modalRota) modalRota.close();
});

formRota.addEventListener("submit", async function (event) {
    event.preventDefault();

    mensagemRota.textContent = "Cadastrando...";

    try {
        const resposta = await fetch("processa_rota.php", {
            method: "POST",
            body: new FormData(formRota)
        });

        const dados = await resposta.json();

        mensagemRota.textContent = dados.mensagem;

        if (dados.ok) {

             const selectRota = document.getElementById("cadastro-rota");
            selectRota.add(new Option(formRota.nome.value, dados.id));

            formRota.reset();
        }
    } catch (erro) {
        mensagemRota.textContent = "Não foi possível cadastrar a rota.";
        console.error(erro);
    }
});

const modalSensor = document.getElementById("modalSensor");
const formSensor = modalSensor.querySelector("form");
const mensagemSensor = document.getElementById("mensagemSensor");

document.getElementById("abrirModalSensor").addEventListener("click", function (event) {
    event.preventDefault();
    mensagemSensor.textContent = "";
    modalSensor.showModal();
});

document.getElementById("fecharModalSensor").addEventListener("click", () => modalSensor.close());

modalSensor.addEventListener("click", function (event) {
    if (event.target === modalSensor) modalSensor.close();
});

formSensor.addEventListener("submit", async function (event) {
    event.preventDefault();

    mensagemSensor.textContent = "Cadastrando...";

    try {
        const resposta = await fetch("processa_sensor.php", {
            method: "POST",
            body: new FormData(formSensor)
        });

        const dados = await resposta.json();

        mensagemSensor.textContent = dados.mensagem;

        if (dados.ok) {
            formSensor.reset();
        }
    } catch (erro) {
        mensagemSensor.textContent = "Não foi possível cadastrar o sensor.";
        console.error(erro);
    }
});

const modalFuncionario = document.getElementById("modalFuncionario");
const formFuncionario = modalFuncionario.querySelector("form");
const mensagemFuncionario = document.getElementById("mensagemFuncionario");

document.getElementById("abrirModalFuncionario").addEventListener("click", function (event) {
    event.preventDefault();
    mensagemFuncionario.textContent = "";
    modalFuncionario.showModal();
});

document.getElementById("fecharModalFuncionario").addEventListener("click", () => modalFuncionario.close());

modalFuncionario.addEventListener("click", function (event) {
    if (event.target === modalFuncionario) modalFuncionario.close();
});

formFuncionario.addEventListener("submit", async function (event) {
    event.preventDefault();

    mensagemFuncionario.textContent = "Cadastrando...";

    try {
        const resposta = await fetch("processa_funcionario.php", {
            method: "POST",
            body: new FormData(formFuncionario)
        });

        const dados = await resposta.json();

        mensagemFuncionario.textContent = dados.mensagem;

        if (dados.ok) {

            // coloca o funcionário novo na lista do modal de trem
            const selectFuncionario = document.getElementById("funcionario_id");
            selectFuncionario.add(new Option(dados.id + " - " + formFuncionario.nome.value, dados.id));

            formFuncionario.reset();
        }
    } catch (erro) {
        mensagemFuncionario.textContent = "Não foi possível cadastrar o funcionário.";
        console.error(erro);
    }
});

</script>

</body>
</html>