document.getElementById("FormsCadastro").addEventListener("submit", function (e) {

    let nome = document.getElementById("cadastro-nome").value;
    let email = document.getElementById("cadastro-email").value;
    let telefone = document.getElementById("cadastro-telefone").value;
    let senha = document.getElementById("cadastro-senha").value;


    if (nome.length < 3) {
        return alert("Nome inválido");
    }


    if (!email.includes("@") || !email.includes(".")) {
        return alert("Email inválido");
    }


    if (telefone.length < 8) {
        return alert("Telefone inválido");
    }


    if (senha.length < 6) {
        return alert("A senha deve ter pelo menos 6 caracteres");
    }


    let informações = [{
        nome,
        email,
        telefone,
        senha
    }];


    alert("Cadastro realizado com sucesso!\n\n" + 
        "Nome: " + nome + "\n" +
        "Email: " + email + "\n" +
        "Telefone: " + telefone
    );

    console.log(JSON.stringify(informações, null, 2));
});