<?php
session_start();
require('fpdf186/fpdf.php'); // Asegúrate que esta ruta sea correcta

if (!isset($_SESSION['administrativo_id'])) {
    $_SESSION['administrativo_id'] = 1; // simular login
}
$admin_id = $_SESSION['administrativo_id'];

// Conexión DB
$host = "localhost";
$dbname = "mydb";
$username = "root";
$password = "";
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error conexión: " . $conn->connect_error);
}

function obtener_formularios_no_reportados($conn, $fecha_inicio, $fecha_fin) {
    $sql = "
    SELECT 
        f.id AS formulario_id,
        e.nombre, e.apellido, e.documento, e.semestre, e.carrera,
        f.fecha,
        a.id AS alerta_id,
        a.resultado_prediccion
    FROM formulario f
    JOIN estudiante e ON f.estudiante_id = e.id
    JOIN alerta a ON a.formulario_id = f.id
    LEFT JOIN reporte r ON r.alerta_id = a.id
    WHERE f.fecha BETWEEN ? AND ?
      AND r.id IS NULL
    ORDER BY f.fecha DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmt->execute();
    return $stmt->get_result();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['formularios'])) {
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $formularios_seleccionados = $_POST['formularios'];

    if (empty($formularios_seleccionados)) {
        $error = "Debe seleccionar al menos un formulario para generar el reporte.";
    } else {
        // Crear PDF (orientación horizontal para más espacio)
        $pdf = new FPDF('L','mm','A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Reporte de Formularios', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, "Fecha: " . date('Y-m-d H:i:s'), 0, 1);
        $pdf->Ln(5);

        // Encabezado tabla con anchos amplios para evitar aplastamiento
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(15, 10, 'ID', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Nombre', 1);
        $pdf->Cell(40, 10, 'Apellido', 1);
        $pdf->Cell(35, 10, 'Documento', 1);
        $pdf->Cell(20, 10, 'Semestre', 1, 0, 'C');
        $pdf->Cell(50, 10, 'Carrera', 1);
        $pdf->Cell(30, 10, 'Fecha', 1);
        $pdf->Cell(30, 10, 'Predicción', 1, 0, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        $fecha_reporte = date('Y-m-d');
        $stmt_insert = $conn->prepare("INSERT INTO reporte (administrativo_id, alerta_id, fecha) VALUES (?, ?, ?)");

        foreach ($formularios_seleccionados as $alerta_id) {
            $sql = "
                SELECT 
                    f.id AS formulario_id,
                    e.nombre, e.apellido, e.documento, e.semestre, e.carrera,
                    f.fecha,
                    a.resultado_prediccion
                FROM alerta a
                JOIN formulario f ON a.formulario_id = f.id
                JOIN estudiante e ON f.estudiante_id = e.id
                WHERE a.id = ?
                LIMIT 1
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $alerta_id);
            $stmt->execute();
            $res = $stmt->get_result();
            $fila = $res->fetch_assoc();

            $resultado_texto = ($fila['resultado_prediccion'] === null) ? 'Sin predicción' : ($fila['resultado_prediccion'] == 1 ? 'Positivo' : 'Negativo');

            $pdf->Cell(15, 8, $fila['formulario_id'], 1, 0, 'C');
            $pdf->Cell(40, 8, utf8_decode($fila['nombre']), 1);
            $pdf->Cell(40, 8, utf8_decode($fila['apellido']), 1);
            $pdf->Cell(35, 8, $fila['documento'], 1);
            $pdf->Cell(20, 8, $fila['semestre'], 1, 0, 'C');
            $pdf->Cell(50, 8, utf8_decode($fila['carrera']), 1);
            $pdf->Cell(30, 8, $fila['fecha'], 1);
            $pdf->Cell(30, 8, $resultado_texto, 1, 0, 'C');
            $pdf->Ln();

            // Guardar registro en reporte
            $stmt_insert->bind_param("iis", $admin_id, $alerta_id, $fecha_reporte);
            $stmt_insert->execute();
        }

        // Descargar el PDF
        $pdf->Output('D', 'formulario_reporte_' . date('Ymd_His') . '.pdf');
        exit;
    }
}

// Mostrar formulario con filtro
$fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
$fecha_fin = $_POST['fecha_fin'] ?? date('Y-m-d');
$formularios = obtener_formularios_no_reportados($conn, $fecha_inicio, $fecha_fin);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Generar Reporte PDF de Formularios</title>
    <style>
        table {
            border-collapse: collapse;
            width: 95%;
            margin: 10px auto;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 10px;
            text-align: left;
        }
        th {
            background: #eee;
        }
        form {
            width: 95%;
            margin: 10px auto;
        }
        .center {
            text-align: center;
        }
        .mensaje, .error {
            width: 95%;
            margin: 10px auto;
            padding: 10px;
            border-radius: 4px;
        }
        .mensaje {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <form action="listar_formularios.php" method="get">
        <button type="submit">Regresar</button>
    </form>
<h2 class="center">Generar Reporte PDF de Formularios</h2>

<form method="post" action="">
    <label>Fecha inicio: <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>" required></label>
    <label>Fecha fin: <input type="date" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>" required></label><br><br>
    <button type="submit" name="filtrar">Filtrar</button>
</form>

<?php if (!empty($error)): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($formularios && $formularios->num_rows > 0): ?>
<form method="post" action="">
    <input type="hidden" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
    <input type="hidden" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">

    <table>
        <thead>
            <tr>
                <th>Seleccionar</th>
                <th>ID Formulario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Documento</th>
                <th>Semestre</th>
                <th>Carrera</th>
                <th>Fecha</th>
                <th>Resultado Predicción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $formularios->fetch_assoc()): ?>
            <tr>
                <td><input type="checkbox" name="formularios[]" value="<?= $row['alerta_id'] ?>"></td>
                <td><?= htmlspecialchars($row['formulario_id']) ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['apellido']) ?></td>
                <td><?= htmlspecialchars($row['documento']) ?></td>
                <td><?= htmlspecialchars($row['semestre']) ?></td>
                <td><?= htmlspecialchars($row['carrera']) ?></td>
                <td><?= htmlspecialchars($row['fecha']) ?></td>
                <td><?= $row['resultado_prediccion'] === null ? 'Sin predicción' : ($row['resultado_prediccion'] == 1 ? 'Positivo' : 'Negativo') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="center">
        <button type="submit">Generar PDF</button>
    </div>
</form>
<?php else: ?>
<p class="center">No hay formularios nuevos sin reporte para las fechas seleccionadas.</p>
<?php endif; ?>

</body>
</html>
