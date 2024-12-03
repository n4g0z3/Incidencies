<?php
// Datos de conexión
$host = "mysql";
$dbname = "incidencias";
$username = "root";
$password = "root";

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>