
<?php
session_start();
include_once("../infra/conexao.php");

if (!empty($_COOKIE["de_train_token"])) {
    $token = $_COOKIE["de_train_token"];

    if (preg_match('/\A[a-f0-9]{64}\z/', $token)) {
        $hash = hash("sha256", $token);
        $stmt = $conexao->prepare(
            "DELETE FROM sessoes_persistentes WHERE token_hash = ?"
        );
        $stmt->bind_param("s", $hash);
        $stmt->execute();
        $stmt->close();
    }
}

setcookie("de_train_token", "", [
    "expires" => time() - 3600,
    "path" => "/",
    "httponly" => true,
    "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
    "samesite" => "Lax"
]);

$_SESSION = [];
session_destroy();

$conexao->close();

header("Location: login.php");
exit();
?>