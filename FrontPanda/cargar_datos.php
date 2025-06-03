<?php
include 'database.php';

if (isset($_GET['tipo'])) {
    if ($_GET['tipo'] === 'productos') {
        $query = "SELECT id_producto, nombre_producto FROM productos";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['id_producto']}'>{$row['nombre_producto']}</option>";
        }
    }

    if ($_GET['tipo'] === 'mediopago') {
        $query = "SELECT id_MedioPago, tipoMedioPago FROM mediopago";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['id_MedioPago']}'>{$row['tipoMedioPago']}</option>";
        }
    }
}
?>