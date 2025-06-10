<?php
include 'database.php';

if (isset($_POST['id_producto'])) {
    $id_producto = intval($_POST['id_producto']);

    $sql = "SELECT precio_venta FROM productos WHERE id_producto = $id_producto";
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['precio_venta' => $row['precio_venta']]);
    } else {
        echo json_encode(['precio_venta' => 0]);
    }
} else {
    echo json_encode(['precio_venta' => 0]);
}
?>

