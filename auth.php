<?php
// panel/auth.php
session_start();

function is_logged_in(): bool {
    return !empty($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true;
}

function require_auth() {
    if (!is_logged_in()) {
        // guarda la url para volver después (opcional)
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
        header('Location: /login.php');
        exit;
    }
}

function login_ok($user, $pass): bool {
    // compara con las credenciales en las variables de entorno (setear con fly secrets)
    $ADMIN_USER = getenv('ADMIN_USER') ?: 'admin';
    $ADMIN_PASS = getenv('ADMIN_PASS') ?: '';

    // comparar usuario
    if (!hash_equals($ADMIN_USER, $user)) return false;

    // comparar contraseña en forma segura
    return hash_equals($ADMIN_PASS, $pass);
}

function do_logout() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}
