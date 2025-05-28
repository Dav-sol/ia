<?php
// listar_formularios.php

// Parámetros de conexión a la base de datos
$host = "localhost";
$dbname = "mydb";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener formularios con estudiante, alerta, semestre y carrera
$sql = "SELECT 
            f.id AS formulario_id,
            e.nombre AS estudiante_nombre,
            e.apellido AS estudiante_apellido,
            e.documento AS estudiante_documento,
            e.semestre AS estudiante_semestre, /* Agregado: Semestre del estudiante */
            e.carrera AS estudiante_carrera,   /* Agregado: Carrera del estudiante */
            f.fecha AS fecha_formulario,
            a.resultado_prediccion
        FROM formulario f
        LEFT JOIN estudiante e ON f.estudiante_id = e.id
        LEFT JOIN alerta a ON a.formulario_id = f.id
        ORDER BY f.fecha DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Formularios</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
    <form action="dashboard.php" method="get">
        <button type="submit">Regresar</button>
    </form>
    <form action="generar_reporte.php" method="get">
        <button type="submit">Generar Reporte</button>
    </form>
    <h2 style="text-align:center;">Listado de Formularios</h2>

    <table>
        <thead>
            <tr>
                <th>ID Formulario</th>
                <th>Nombre Estudiante</th>
                <th>Apellido Estudiante</th>
                <th>Documento Estudiante</th>
                <th>Semestre</th> <th>Carrera</th>  <th>Fecha del Formulario</th>
                <th>Resultado Predicción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Para mostrar resultado_prediccion, podrías interpretar 0/1 o null
                    $resultado = isset($row['resultado_prediccion']) ? 
                        ($row['resultado_prediccion'] == 1 ? 'Positivo' : 'Negativo') : 'Sin predicción';

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['formulario_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estudiante_nombre']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estudiante_apellido']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estudiante_documento']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estudiante_semestre']) . "</td>"; // Mostrar semestre
                    echo "<td>" . htmlspecialchars($row['estudiante_carrera']) . "</td>";   // Mostrar carrera
                    echo "<td>" . htmlspecialchars($row['fecha_formulario']) . "</td>";
                    echo "<td>" . $resultado . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8' style='text-align:center;'>No hay formularios registrados.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>

<?php
$conn->close();
?>