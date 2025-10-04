<?php
// panel/add.php
require_once __DIR__ . '/auth.php';
require_auth();

$serversFile = '/servers.json';
$servers = [];

if (file_exists($serversFile)) {
    $servers = json_decode(file_get_contents($serversFile), true) ?: [];
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $clave = trim($_POST['clave'] ?? '');
    $origen = trim($_POST['origen'] ?? '');
    $activo = isset($_POST['activo']) ? true : false;

    if ($id === '' || $clave === '' || $origen === '') {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // validar ID único
        foreach ($servers as $s) {
            if ($s['id'] === $id) {
                $error = 'El ID ya existe. Elige otro.';
                break;
            }
        }
    }

    if ($error === '') {
        $servers[] = [
            'id' => $id,
            'clave' => $clave,
            'origen' => $origen,
            'activo' => $activo
        ];

        // escribir con bloqueo
        $tmp = json_encode($servers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents($serversFile . '.tmp', $tmp, LOCK_EX);
        rename($serversFile . '.tmp', $serversFile);

        // recarga nginx (intentar)
        @exec('nginx -s reload 2>/dev/null');

        header('Location: /index.php');
        exit;
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Agregar servidor</title></head>
<body>
  <h2>Agregar servidor</h2>
  <?php if ($error): ?><p style="color:red;"><?=htmlspecialchars($error)?></p><?php endif; ?>
  <form method="post">
    <label>ID (sin espacios): <input name="id" required></label><br><br>
    <label>Clave (stream key proxy): <input name="clave" required></label><br><br>
    <label>Origen (RTMP completo con user:pass si aplica): <input name="origen" required style="width:600px"></label><br><br>
    <label>Activo: <input type="checkbox" name="activo" checked></label><br><br>
    <button type="submit">Agregar</button>
  </form>
  <p><a href="/index.php">Volver</a></p>
</body>
</html>
