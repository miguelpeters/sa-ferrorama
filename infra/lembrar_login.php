
<?php

function lembrarLogin($conexao, $tipo, $id)
{
    $token = bin2hex(random_bytes(32));
    $hash = hash("sha256", $token);
    $expiracao = date("Y-m-d H:i:s", time() + (30 * 86400));

    $sql = "INSERT INTO sessoes_persistentes
            (usuario_id, tipo_usuario, token_hash, expira_em)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("isss", $id, $tipo, $hash, $expiracao);
    $stmt->execute();
    $stmt->close();

    setcookie("de_train_token", $token, [
        "expires" => time() + (30 * 86400),
        "path" => "/",
        "httponly" => true,
        "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
        "samesite" => "Lax"
    ]);
}

function restaurarLogin($conexao)
{
    if (isset($_SESSION["gerente_id"]) || isset($_SESSION["id"])) {
        return;
    }

    if (empty($_COOKIE["de_train_token"])) {
        return;
    }

    $token = $_COOKIE["de_train_token"];

    if (!preg_match('/\A[a-f0-9]{64}\z/', $token)) {
        return;
    }

    $hash = hash("sha256", $token);

    $sql = "SELECT usuario_id, tipo_usuario
            FROM sessoes_persistentes
            WHERE token_hash = ? AND expira_em > NOW()";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $hash);
    $stmt->execute();
    $sessao = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$sessao) {
        return;
    }

    $id = (int) $sessao["usuario_id"];
    $tipo = $sessao["tipo_usuario"];

    if ($tipo === "gerente") {
        $sql = "SELECT id, nome, email FROM gerentes WHERE id = ?";
    } elseif ($tipo === "funcionario") {
        $sql = "SELECT id, nome, email FROM funcionarios WHERE id = ?";
    } elseif ($tipo === "usuario") {
        $sql = "SELECT id, nome, email FROM usuarios WHERE id = ?";
    } else {
        return;
    }

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$usuario) {
        return;
    }

    session_regenerate_id(true);

    if ($tipo === "gerente") {
        $_SESSION["gerente_id"] = $usuario["id"];
        $_SESSION["gerente_nome"] = $usuario["nome"];
        $_SESSION["gerente_email"] = $usuario["email"];
    } else {
        $_SESSION["tipo"] = $tipo;
        $_SESSION["id"] = $usuario["id"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];
    }
}
?>