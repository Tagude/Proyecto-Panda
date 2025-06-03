<?php
include 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productos = $_POST['producto'];
    $cantidades = $_POST['cantidad'];
    $precios_unitarios = $_POST['precio_unitario'];
    $id_mediopago = $_POST['medio_pago'];
    $fecha = date('Y-m-d');

    // Obtener nombre del medio de pago
    $stmt_pago = $conn->prepare("SELECT tipoMedioPago FROM mediopago WHERE id_MedioPago = ?");
    $stmt_pago->bind_param("i", $id_mediopago);
    $stmt_pago->execute();
    $res_pago = $stmt_pago->get_result();
    $nombre_mediopago = $res_pago->fetch_assoc()['tipoMedioPago'] ?? 'Desconocido';

    echo "<h1>🧾 Recibo de venta</h1>";
    echo "<p><strong>Fecha:</strong> $fecha</p>";
    echo "<p><strong>Medio de pago:</strong> $nombre_mediopago</p>";

    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr>";

    $total = 0;

    for ($i = 0; $i < count($productos); $i++) {
        $id_producto = intval($productos[$i]);
        $cantidad = intval($cantidades[$i]);
        $precio_unitario = floatval($precios_unitarios[$i]);
        $subtotal = $cantidad * $precio_unitario;
        $total += $subtotal;

        // Registrar la venta
        $stmt = $conn->prepare("INSERT INTO ventas (id_producto, cantidad, precio_unitario, id_mediopago, fecha) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiids", $id_producto, $cantidad, $precio_unitario, $id_mediopago, $fecha);
        $stmt->execute();

        // Actualizar stock
        $stmt_update = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");
        $stmt_update->bind_param("ii", $cantidad, $id_producto);
        $stmt_update->execute();

        // Obtener nombre del producto
        $stmt_nombre = $conn->prepare("SELECT nombre_producto FROM productos WHERE id_producto = ?");
        $stmt_nombre->bind_param("i", $id_producto);
        $stmt_nombre->execute();
        $result = $stmt_nombre->get_result();
        $nombre = $result->fetch_assoc()['nombre_producto'] ?? 'Producto desconocido';

        echo "<tr>
                <td>{$nombre}</td>
                <td>{$cantidad}</td>
                <td>$" . number_format($precio_unitario) . "</td>
                <td>$" . number_format($subtotal) . "</td>
              </tr>";
    }

    echo "<tr><td colspan='3'><strong>Total</strong></td><td><strong>$" . number_format($total) . "</strong></td></tr>";
    echo "</table>";

    echo "<br><button onclick='window.print()'>🖨️ Imprimir Recibo</button>";
    echo "<br><br><a href='home.php'><button>🏠 Volver al Inicio</button></a>";
} else {
    echo "Acceso no permitido.";
}
?>


