<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "mypandabd";

$conn = new mysqli($host, $user, $pass, $db);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>