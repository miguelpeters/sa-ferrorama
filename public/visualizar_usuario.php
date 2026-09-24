<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/style/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <title>Visualização de usuários</title>

  <link rel="icon" href="../assets/imgs/LogoDeTrain.png" type="image/x-icon">
</head>

<body class="body_home">

  <header class="cabecalho">

    <div class="lado-esquerdo">
        <button class="buttonRetornar" onclick="history.back()">DE-TRAIN</button>
        <img id="icon" src="../assets/imgs/LogoDeTrain.png" class="logo">
        <br>

      </nav>
    </div>

    <div class="lado-direito">
      <span>FULANO DE TAL - FUNCIONÁRIO</span>
      <img src="../assets/imgs/LoginIcon.png" class="perfil">
    </div>

  </header>



  <main>

    <div class="container_tabela_usuarios">
      <table class="tabela_usuarios table-bordered" class="tabela_usuarios2">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nome</th>
            <th scope="col">Email</th>
            <th scope="col">Telefone</th>
            <th scope="col">Tipo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <td>Miguel</td>
            <td>miguel@gmail.com</td>
            <td>12345678</td>
            <td>Funcionário</td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>Leonardo</td>
            <td>leonardo@gmail.com</td>
            <td>12345678</td>
            <td>Visitante</td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <td>Theo</td>
            <td>theo@gmail.com</td>
            <td>12345678</td>
            <td>Funcionário</td>
          </tr>
          <tr>
            <th scope="row">4</th>
            <td>Tiago</td>
            <td>tiago@gmail.com</td>
            <td>12345678</td>
            <td>Visitante</td>
          </tr>
          <tr>
            <th scope="row">5</th>
            <td>Marcos</td>
            <td>marcos@gmail.com</td>
            <td>12345678</td>
            <td>Visitante</td>
          </tr>
          <tr>
            <th scope="row">6</th>
            <td>Luiz</td>
            <td>luiz@gmail.com</td>
            <td>12345678</td>
            <td>Funcionário</td>
          </tr>
          <tr>
            <th scope="row">7</th>
            <td>Ramon</td>
            <td>ramon@gmail.com</td>
            <td>12345678</td>
            <td>Visitante</td>
          </tr>
        </tbody>
      </table>
    </div>

  </main>


</body>

</html>