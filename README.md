# SA-Ferrorama
## Equipe: Leonardo Aguiar, Miguel Kormann, Theo Maccari, Tiago Cabral.

## Proposta do sistema:
A proposta do nosso sistema é criar um site que permita visualizar informações sobre trens coletadas a partir de sensores IOT instalados em trilhos.

## Objetivos:
- Permitir a visualização das informações dos trens.
- Coletar informações sobre os sensores IOTs.
- Criar uma plataforma para visualização dos relatórios coletados dos sensores.
- Permitir o gerenciamento de trens, rotas e sensores.

## Tecnologias Utilizadas:
- HTML
- CSS
- Javascript
- PHP
- MySql
- XAMPP

## Metodologias de Desenvolvimento:
- Kanban
- Scrum

## Regras de Negócio:
- É necessário fazer login com uma conta de funcionário para executar as funcionalidades do sistema.
- É necessário estar logado como gerente para cadastrar uma nova conta de funcionário.
- É necessário fazer login como gerente ou funcionário para acessar a visualização das linhas, sensores e trens.
- Apenas gerentes têm acesso à visualização de usuários
- É necessário fazer login como funcionário ou gerente para cadastrar um novo sensor, trem ou rota
- Alertas deverão aparecer caso algum campo seja preenchido incorretamente ou caso algum erro apareça

## Requisitos Funcionais:
- O sistema deve permitir fazer login caso tenha uma conta
- O sistema deve permitir cadastrar um novo funcionário caso esteja usando uma conta de gerente
- O sistema deve permitir visualizar a localização e rota dos trens em tempo real
- O sistema deve permitir visualizar os relatórios enviados a partir dos sensores IOTs
- O sistema deve permitir visualizar, editar e excluir os sensores já registrados
- O sistema deve permitir cadastrar sensores
- O sistema deve manter o usuário conectado no próximo acesso caso não tenha deslogado- 
- O sistema deve permitir visualizar, editar e excluir os trens já registrados
- O sistema deve permitir cadastrar estações
- O sistema deve permitir cadastrar trens
- O sistema deve permitir visualizar, editar e excluir as rotas já registradas

## Requisitos Não-Funcionais:
- O tempo de resposta do site deve ser mínimo
- O sistema deverá apresentar mensagens de confirmação para ações de exclusão ou edição
- As informações dos usuários devem ser protegidas com criptografia
- O site deve possuir alta escalabilidade
- As funções e ferramentas do site devem ser intuitivas

