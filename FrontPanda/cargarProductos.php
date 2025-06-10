<?php
require 'database.php';

$sql = "SELECT id_producto, nombre_producto FROM productos WHERE stock > 0";
$result = $conn->query($sql);
$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode($productos);
?>
