<?php
session_start();
include('conexion.php');

// Verificar si el usuario tiene la sesión iniciada
if (!isset($_SESSION['username']) || $_SESSION['rol'] !== 'cliente') {
    echo "<script>alert('Acceso denegado'); window.location.href = 'login.php';</script>";
    exit();
}

// Manejo del formulario para reportar incidencia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $descripcio = $_POST['descripcio'];
    if (empty($nom) || empty($descripcio)){
        //header("Location: dashboard_cliente.php");
        echo "<script>alert('Pero vamos a ver Pedazo de... i cara calabacín, no ves que la incidencia está vacía, jubilate YA que tus nietos te estan esperando el el parc de catalunya para que les des pan a los patos');</script>";
    } else {

        $tancat = 0; // Por defecto, la incidencia está abierta
    
        $sql = "INSERT INTO incidencia (Nom, Descripcio, Tancat) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nom, $descripcio, $tancat);
    
        if ($stmt->execute()) {
            echo "<script>alert('Incidencia reportada con éxito');</script>";
        } else {
            echo "<script>alert('Error al reportar la incidencia');</script>";
        }
    
        $stmt->close();
    }
}

// Obtener todas las incidencias
$sql = "SELECT ID_incidencia, Nom, Descripcio, Tancat FROM incidencia ORDER BY Tancat ASC, Nom ASC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cliente - Reportar Incidencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .form-group button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .incidencias {
            margin-top: 20px;
        }
        .incidencias table {
            width: 100%;
            border-collapse: collapse;
        }
        .incidencias th, .incidencias td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .incidencias th {
            background-color: #007bff;
            color: #fff;
        }
        .status-abierto {
            color: red;
            font-weight: bold;
        }
        .status-cerrado {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reportar Incidencia</h1>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" name="nom" placeholder="Nombre de la incidencia" required>
            </div>
            <div class="form-group">
                <textarea name="descripcio" placeholder="Descripción de la incidencia" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Enviar</button>
            </div>
        </form>

        <div class="incidencias">
            <h2>Listado de Incidencias</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['ID_incidencia']); ?></td>
                            <td><?php echo htmlspecialchars($row['Nom']); ?></td>
                            <td><?php echo htmlspecialchars($row['Descripcio']); ?></td>
                            <td class="<?php echo $row['Tancat'] ? 'status-cerrado' : 'status-abierto'; ?>">
                                <?php echo $row['Tancat'] ? 'Cerrado' : 'Abierto'; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
