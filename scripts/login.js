document.querySelector("form").addEventListener("submit", function (e) {

    let email = document.getElementById("login-email").value.trim();
    let senha = document.getElementById("login-senha").value;

    if (email === "") {
        e.preventDefault();
        alert("Digite seu email.");
        return;
    }

    if (!email.includes("@") || !email.includes(".")) {
        e.preventDefault();
        alert("Digite um email válido.");
        return;
    }

    if (senha === "") {
        e.preventDefault();
        alert("Digite sua senha.");
        return;
    }

});