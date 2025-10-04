<?php
// panel/index.php
require_once __DIR__ . '/auth.php';
require_auth();

$serversFile = '/servers.json';
$servers = [];
if (file_exists($serversFile)) {
    $json = file_get_contents($serversFile);
    $servers = json_decode($json, true) ?: [];
}

?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Panel RTMP - Admin</title></head>
<body>
  <h1>Panel RTMP - Admin</h1>
  <p><a href="/logout.php">Cerrar sesión</a></p>
  <p><a href="/add.php">Agregar servidor</a></p>
  <table border="1" cellpadding="6" cellspacing="0">
    <tr><th>ID</th><th>Clave</th><th>Origen</th><th>Activo</th><th>Acciones</th></tr>
    <?php foreach ($servers as $s): ?>
      <tr>
        <td><?=htmlspecialchars($s['id'])?></td>
        <td><?=htmlspecialchars($s['clave'])?></td>
        <td style="max-width:400px;overflow:hidden;"><?=htmlspecialchars($s['origen'])?></td>
        <td><?=!empty($s['activo']) ? '✅' : '❌'?></td>
        <td>
          <a href="/edit.php?id=<?=urlencode($s['id'])?>">Editar</a> |
          <a href="/delete.php?id=<?=urlencode($s['id'])?>" onclick="return confirm('Eliminar?')">Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
