<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.html");
    exit();
}

$nombre = $_SESSION['nombre_usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Home</title>
    <link rel="stylesheet" href="CSS/styles.css" />
    <link rel="shortcut icon" href="IMG/PANDA icon.ico" type="image/x-icon" />
</head>
<body>

<header>
    <div class="logo">
        <img class="logos" src="IMG/PANDA.webp" alt="Logo">
    </div>
    <div class="opciones">
        <a href="logout.php" class="opcion">
            <img src="IMG/sesion.png" alt="Cerrar Sesión">
            <span>Cerrar Sesión</span>
        </a>
    </div>
</header>

<main>
    <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?> 👋</h1>
    <div class="opciones">
        <a href="registrarVenta.html" class="opcion">
            <img src="IMG/vender.png" alt="Registrar">
            <span>Registrar Venta</span>
        </a>
        <a href="inventario.html" class="opcion">
            <img src="IMG/inventario.png" alt="Inventario">
            <span>Inventario</span>
        </a>
        <a href="ventas.html" class="opcion">
            <img src="IMG/reportes.png" alt="Ventas">
            <span>Reportes</span>
        </a>
        <a href="cierre.html" class="opcion">
            <img src="IMG/cierre.png" alt="Cierre">
            <span>Cierre de caja</span>
        </a>
    </div>
</main>

</body>
</html>