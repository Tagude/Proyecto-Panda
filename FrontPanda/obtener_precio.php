<?php
include 'database.php';

if (isset($_GET['id_producto'])) {
    $id = $_GET['id_producto'];

    // Usa sentencia preparada para seguridad
    $stmt = $conn->prepare("SELECT precio FROM productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($precio);
    $stmt->fetch();
    echo json_encode(['precio' => $precio ?? 0]);
    $stmt->close();
}
?>