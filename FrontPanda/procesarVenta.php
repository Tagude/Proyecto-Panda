<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productos = $_POST['producto']; // Array de id_producto
    $cantidades = $_POST['cantidad'];
    $precios = $_POST['precio'];
    $medioPago = $_POST['medioPago'] ?? null;
    $fecha = date('Y-m-d H:i:s');

    if (!$medioPago) {
        die("Error: Medio de pago no especificado.");
    }

    foreach ($productos as $i => $id_producto) {
        $cantidad = intval($cantidades[$i]);
        $precio_unitario = floatval($precios[$i]);

        if (!$id_producto || $cantidad <= 0 || $precio_unitario <= 0) {
            continue; // Evita datos incompletos
        }

        // Inserta la venta
        $sql = "INSERT INTO ventas (id_producto, cantidad, precio_unitario, id_mediopago, fecha) 
                VALUES ('$id_producto', '$cantidad', '$precio_unitario', '$medioPago', '$fecha')";
        
        if (!$conn->query($sql)) {
            die("Error al registrar venta: " . $conn->error);
        }

        // Actualiza stock
        $sqlUpdate = "UPDATE productos SET stock = stock - $cantidad WHERE id_producto = '$id_producto'";
        $conn->query($sqlUpdate);
    }

    echo "<h3>Ticket de venta</h3>";
    echo "<p>Venta registrada con éxito</p>";
    // Obtener nombre del medio de pago
$sqlMedio = "SELECT tipoMedioPago FROM mediopago WHERE id_MedioPago = '$medioPago'";
$resMedio = $conn->query($sqlMedio);
$medioPagoNombre = $resMedio->fetch_assoc()['tipoMedioPago'] ?? 'Desconocido';

// Preparar detalles para la sesión
$_SESSION['venta'] = [
    'productos' => [],
    'total' => 0,
    'valorPagado' => floatval($_POST['valorPagado']),
    'medioPago' => $medioPagoNombre
];

foreach ($productos as $i => $id_producto) {
    $cantidad = intval($cantidades[$i]);
    $precio_unitario = floatval($precios[$i]);

    if (!$id_producto || $cantidad <= 0 || $precio_unitario <= 0) {
        continue;
    }

    // Obtener nombre del producto
    $resNombre = $conn->query("SELECT nombre_producto FROM productos WHERE id_producto = '$id_producto'");
    $nombreProducto = $resNombre->fetch_assoc()['nombre_producto'] ?? 'Producto';

    $_SESSION['venta']['productos'][] = [
        'nombre' => $nombreProducto,
        'cantidad' => $cantidad,
        'precio' => $precio_unitario
    ];

    $_SESSION['venta']['total'] += $precio_unitario * $cantidad;
}

// Redirigir a la página del ticket
header("Location: ticket.php");
exit;
} else {
    echo "Método no permitido";
}
?>
