


<?php
session_start();
include('conexion.php');

// Obtener datos del formulario
$username = $_POST['username'];
$password = $_POST['password'];

// Consulta para verificar el usuario y rol
$sql = "SELECT r.rol FROM usuarios u INNER JOIN roles r ON u.id_rol = r.id WHERE u.username = ? AND u.password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$stmt->bind_result($rol);
$stmt->fetch();

if ($rol) {
    $_SESSION['username'] = $username;
    $_SESSION['rol'] = $rol;

    // Redireccionar según el rol
    if ($rol === 'Tecnico') {
        header("Location: dashboard_tecnico.php");
    } elseif ($rol === 'Cliente') {
        header("Location: dashboard_cliente.php");
    }
} else {
    // Si las credenciales no son válidas
    echo "<script>alert(' ¡¡Eh tú!!, deja ya las inyecciones de SQL, ponte a hacer cables de red o comprate un perro y deja de molestarnos y si te echas novia y desapareces de la informática mejor'); window.location.href = 'login.php';</script>";
    
}

$stmt->close();
$conn->close();
?>