<?php
// panel/login.php
require_once __DIR__ . '/auth.php';

if (is_logged_in()) {
    header('Location: /panel/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';

    if (login_ok($user, $pass)) {
        $_SESSION['admin_logged'] = true;
        // redirige a la página anterior o index
        $to = $_SESSION['return_to'] ?? '/index.php';
        unset($_SESSION['return_to']);
        header("Location: $to");
        exit;
    } else {
        $error = "Usuario o contraseña incorrecta.";
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login - Panel RTMP</title></head>
<body>
  <h2>Acceso administrador</h2>
  <?php if ($error): ?><p style="color:red;"><?=htmlspecialchars($error)?></p><?php endif; ?>
  <form method="post">
    <label>Usuario: <input name="user" required></label><br><br>
    <label>Contraseña: <input name="pass" type="password" required></label><br><br>
    <button type="submit">Entrar</button>
  </form>
</body>
</html>
