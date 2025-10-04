<?php
$data = json_decode(file_get_contents("/app/servers.json"), true);

echo "<h1>Panel RTMP Proxy</h1>";
echo "<a href='add.php'>Agregar nuevo servidor</a><br><br>";

foreach ($data as $s) {
    echo "<b>{$s['id']}</b> - clave: {$s['clave']} - ";
    echo $s['activo'] ? "✅ Activo" : "❌ Inactivo";
    echo " | <a href='edit.php?id={$s['id']}'>Editar</a>";
    echo " | <a href='delete.php?id={$s['id']}'>Eliminar</a><br>";
}
?>
