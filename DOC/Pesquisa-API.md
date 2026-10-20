# Pesquisa API’S

API (Application Programming Interface, ou Interface de Programação de Aplicativos) é um conjunto de regras, protocolos e ferramentas que permite que diferentes softwares se comuniquem e troquem dados entre si

## Como funciona a comunicação entre sistemas por meio de APIs;

A arquitetura da API geralmente é explicada em termos de cliente e servidor. A aplicação que envia a solicitação é chamada de cliente e a aplicação que envia a resposta é chamada de servidor. 

## O que são APIs REST;

Uma API REST é uma interface de programação de aplicativos (API) que segue os princípios de design do estilo arquitetônico REST (transferência de estado representacional), utilizado para conectar sistemas hipermídia distribuídos. As APIs REST também podem ser chamadas de APIs RESTful ou APIs web RESTful.

## O papel dos métodos HTTP, como GET, POST, PUT, PATCH e DELETE;

O método GET é usado para recuperar dados em um servidor. Os clientes podem usar o método GET para acessar todos os recursos de um determinado tipo ou para acessar um recurso específico.

O método POST é usado para criar novos recursos. Por exemplo, se o gerente de uma loja virtual quisesse adicionar um novo produto ao banco de dados, ele enviaria uma requisição POST para o /products endpoint.

O método PUT é usado para substituir um recurso existente por uma versão atualizada. Esse método funciona substituindo todo o recurso pelos dados incluídos no corpo da requisição. 

O método PATCH é usado para atualizar um recurso existente. É semelhante ao PUT, exceto que o PATCH permite que os clientes atualizem propriedades específicas de um recurso, sem sobrescrever as demais. 

O método DELETE é usado para remover dados de um banco de dados. Quando um cliente envia uma solicitação DELETE, ele está solicitando a remoção do recurso no URL especificado.

## O que são requisições e respostas HTTP;

Uma requisição HTTP é uma mensagem enviada por um cliente (como o seu navegador ou um aplicativo) para um servidor com o objetivo de solicitar um recurso ou uma ação.
Uma resposta HTTP é a mensagem de retorno enviada pelo servidor para o cliente, informando o resultado daquela solicitação (com o conteúdo pedido ou um código de status).

## O que são endpoints;

Um endpoint de API é um local digital onde uma interface de programação de aplicativos (API) recebe chamadas de API, também conhecidas como solicitações de API, para recursos em seu servidor.

## Como funciona a troca de dados no formato JSON;

A aplicação de origem (no back-end, escrito em Python, Java, Node.js, etc.) pega um objeto nativo daquela linguagem e o converte em uma string de texto no formato JSON. Essa string de texto é leve e compacta. Ela viaja pela rede  do servidor até o destinatário. O sistema que recebe o texto faz a deserialização 

## Diferenças entre uma API e um sistema web tradicional;

A única diferença é que um serviço da Web facilita a interação entre duas máquinas em uma rede. Uma API atua como uma interface entre dois aplicativos diferentes para que eles possam se comunicar.

## Como uma aplicação PHP pode consumir uma API;

Uma aplicação PHP pode consumir uma API através de requisições HTTP utilizando principalmente a cURL (extensão nativa mais robusta), a função nativa file_get_contents() (para casos simples), ou bibliotecas de terceiros como o Guzzle ou Unirest

## Como uma API pode disponibilizar informações para outros sistemas;

Integração via API é a conexão entre aplicações por meio de interfaces que definem quais dados ou operações podem ser solicitados. Um sistema envia uma requisição com endereço, método, credenciais e dados; a API processa a chamada e devolve uma resposta com resultado ou erro. Para funcionar de forma confiável, a integração também precisa de contratos, validações, segurança, tratamento de falhas, versionamento e monitoramento.

## Importância da autenticação, autorização e segurança no consumo de APIs;

A autenticação ajuda a garantir que usuários autorizados possam acessar os recursos de que precisam, protegendo dados confidenciais e mantendo a segurança das API.

Interfaces de programação de aplicativos (APIs) são pilares fundamentais dos ecossistemas modernos de TI, permitindo que aplicações, bancos de dados, dispositivos e outros componentes de TI troquem dados entre arquiteturas, ambientes e protocolos.

Exemplos de utilização de APIs em sistemas empresariais.

Integração E-commerce e ERP: Atualiza o estoque, o financeiro e emite notas fiscais automaticamente assim que uma venda é feita no site.

Gateways de Pagamento: Processa transações via Pix, boleto ou cartão de crédito de forma segura e instantânea no checkout.

Conexão com Marketplaces: Sincroniza preços e produtos com plataformas como Amazon e Mercado Livre em tempo real.
