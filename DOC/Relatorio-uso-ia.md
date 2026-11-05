# Relatório de Utilização da IA

## Agente de IA utilizado
Claude (Anthropic), utilizado por meio da interface de chat (claude.ai).

## Objetivo de cada utilização
- Obter um mapa que mostrasse vento e chuva, sem as linhas de rotas (a imagem anterior era apenas um placeholder).
- Fazer com que o CEP informado na "Consulta de CEP" altere também o local do clima e do mapa.
- Diagnosticar e corrigir erros que surgiram após a integração (mapa com erro "Forbidden" e clima carregando indefinidamente).

## Principais prompts fornecidos
- "Na real eu só queria um mapinha que mostrasse os ventos e chuva mesmo, sem precisar das linhas, aquele mapa que tá ali é só um placeholder."
- "Tem alguma forma de, através do CEP informado, mudar o local da API de temperatura e clima?"
- "Ficou assim agora, o mapa no meio bugou, e na API de clima fica carregando infinito." (com captura de tela)
- Perguntas de acompanhamento sobre onde exatamente alterar o início da função atualizarClima, se o trecho estava correto e se era possível fechar as chaves antes do try.

## Trecho, arquivos e funcionalidades produzidos com auxílio da IA
- Arquivo principal afetado: home_gerente.php (painel do gerente) e o arquivo de estilos style.css.
- Clima dinâmico: adaptação da função atualizarClima(latitude, longitude) para receber coordenadas como parâmetro, com a variável localAtual e timezone=auto na API Open-Meteo.
- Mudança de local pelo CEP: nova função mudarLocal(cidade, uf), que:
recebe cidade e UF obtidas pelo ViaCEP;
converte a cidade em latitude/longitude pelo Nominatim (OpenStreetMap);
atualiza a etiqueta do local, o mapa do Windy (via src do iframe) e o clima.
- Script consolidado: versão final do bloco <script> reunindo clima, mudança de local e consulta de CEP.

## Testes executados
- Carregamento do mapa
- Carregamento do clima
- Inspeção de erros -> Console do navegador (F12 → Console, recarregando com Ctrl+F5)
- Consulta de CEP
- Mudança de local

## Reflexões sobre contribuições e limitações da ferramenta
Contribuições:
- Rapidez na pesquisa de soluções: a IA apresentou rapidamente alternativas (Leaflet, Windy, RainViewer, Open-Meteo) e indicou a mais adequada ao objetivo (Windy, por ser gratuito, sem chave de API e já mostrar vento e chuva).
- Integração de APIs: auxiliou a encadear ViaCEP, Nominatim, Open-Meteo e Windy em um fluxo único (CEP → cidade → coordenadas → clima e mapa).
- Revisão do código existente: apontou tags HTML sem fechamento que a equipe ainda não havia percebido.

Limitações:
- Dependência do contexto fornecido: a IA não enxerga o projeto; ela só conhece o que é colado ou mostrado. Algumas instruções iniciais foram ambíguas até que o código completo fosse enviado.
- Diagnóstico por hipótese: o erro "Forbidden" foi diagnosticado por suposição (atributo src), e só foi resolvido de fato conferir o código.
- Necessidade de validação humana: todo o código sugerido precisou ser testado, revisado e adaptado pela equipe antes de funcionar corretamente.