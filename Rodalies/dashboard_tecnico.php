<?php
session_start();
include('conexion.php');

// Verificar si el usuario tiene la sesión iniciada
if (!isset($_SESSION['username']) || $_SESSION['rol'] !== 'tecnico') {
    echo "<script>alert('Acceso denegado'); window.location.href = 'login.php';</script>";
    exit();
}

// Manejo del cierre de incidencias
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_incidencia'])) {
    $id_incidencia = $_POST['id_incidencia'];
    $fecha_cierre = date("Y-m-d H:i:s");

    $sql = "UPDATE incidencia SET Tancat = 1, FechaCierre = ? WHERE ID_incidencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $fecha_cierre, $id_incidencia);

    if ($stmt->execute()) {
        echo "<script>alert('Incidencia cerrada con éxito');</script>";
    } else {
        echo "<script>alert('Error al cerrar la incidencia');</script>";
    }

    $stmt->close();
}

// Obtener todas las incidencias
$sql = "SELECT ID_incidencia, Nom, Descripcio, Tancat, FechaCierre FROM incidencia ORDER BY Tancat ASC, ID_incidencia ASC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Técnico - Gestión de Incidencias</title>
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
        h1 {
            text-align: center;
            color: #333;
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
        .cerrar-btn {
            padding: 5px 10px;
            border: none;
            background-color: #28a745;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }
        .cerrar-btn:hover {
            background-color: #218838;
        }
        .fecha-cierre {
            font-size: 0.9em;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Incidencias</h1>
        <div class="incidencias">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Fecha de Cierre</th>
                        <th>Acción</th>
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
                            <td class="fecha-cierre">
                                <?php echo $row['Tancat'] ? htmlspecialchars($row['FechaCierre']) : 'N/A'; ?>
                            </td>
                            <td>
                                <?php if (!$row['Tancat']) { ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="id_incidencia" value="<?php echo $row['ID_incidencia']; ?>">
                                        <button type="submit" name="cerrar_incidencia" class="cerrar-btn">Cerrar</button>
                                    </form>
                                <?php } else { ?>
                                    -
                                <?php } ?>
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
