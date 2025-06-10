<?php
include 'database.php';

if (!isset($_GET['tipo'])) {
    exit("Parámetro 'tipo' no especificado.");
}

$tipo = $_GET['tipo'];

if ($tipo === 'productos') {

    $sql = "SELECT id_producto, nombre_producto FROM productos WHERE stock > 0";
    $res = $conn->query($sql);

    while ($p = $res->fetch_assoc()) {
        echo "<option value='{$p['id_producto']}'>{$p['nombre_producto']}</option>";
    }

} elseif ($tipo === 'mediopago') {

    $sql = "SELECT id_MedioPago, tipoMedioPago FROM mediopago";
    $res = $conn->query($sql);

    while ($m = $res->fetch_assoc()) {
        echo "<option value='{$m['id_MedioPago']}'>{$m['tipoMedioPago']}</option>";
    }

} else {
    echo "Tipo no reconocido.";
}
?>