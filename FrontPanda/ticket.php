<?php
session_start();

if (!isset($_SESSION['venta'])) {
    echo "No hay datos de venta disponibles.";
    exit;
}

$venta = $_SESSION['venta'];
unset($_SESSION['venta']); // Limpiar después de mostrar
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Venta</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <style>
        .ticket { border: 1px solid #ccc; padding: 20px; width: 400px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        h2, p { text-align: center; }
        .acciones { text-align: center; }
    </style>
</head>
<body>
<div class="ticket">
    <h2>Ticket de Venta</h2>

    <table>
        <tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>
        <?php foreach ($venta['productos'] as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nombre']) ?></td>
                <td><?= $p['cantidad'] ?></td>
                <td>$<?= number_format($p['precio'], 2) ?></td>
                <td>$<?= number_format($p['precio'] * $p['cantidad'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p><strong>Total:</strong> $<?= number_format($venta['total'], 2) ?></p>
    <p><strong>Valor pagado:</strong> $<?= number_format($venta['valorPagado'], 2) ?></p>
    <p><strong>Medio de pago:</strong> <?= htmlspecialchars($venta['medioPago']) ?></p>

    <?php if ($venta['valorPagado'] > $venta['total']): ?>
        <p><strong>Devolución:</strong> $<?= number_format($venta['valorPagado'] - $venta['total'], 2) ?></p>
    <?php endif; ?>

    <div class="acciones">
        <button onclick="window.print()">Imprimir</button>
        <a href="home.php"><button>Volver al inicio</button></a>
    </div>
</div>
</body>
</html>
