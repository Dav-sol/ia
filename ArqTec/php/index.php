<?php 
include 'conexion.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Predicción de Deserción</title>
</head>
<body>

<form action="login.php" method="get">
        <button type="submit">Iniciar sesión</button>
    </form>

    <h2>Formulario de Predicción</h2>
    <form method="POST">
        Documento: <input type="text" name="documento" required><br>
        Nombre: <input type="text" name="nombre" required><br>
        Apellido: <input type="text" name="apellido" required><br>
        Carrera: <input type="text" name="carrera" required><br>
        Semestre: <input type="number" name="semestre" required><br><br>

        <!-- Datos del modelo -->
        Tuition fees up to date: <input type="number" name="Tuition_fees_up_to_date" required><br>
        International: <input type="number" name="International" required><br>
        Curricular units 2nd sem (approved): <input type="number" name="CU2_ap" required><br>
        Curricular units 2nd sem (enrolled): <input type="number" name="CU2_en" required><br>
        Debtor: <input type="number" name="Debtor" required><br>
        Scholarship holder: <input type="number" name="Scholarship_holder" required><br>
        Curricular units 1st sem (approved): <input type="number" name="CU1_ap" required><br>
        Displaced: <input type="number" name="Displaced" required><br>

        <input type="submit" name="submit" value="Predecir">
    </form>

<?php
if (isset($_POST['submit'])) {
    // Verificar si el estudiante existe
    $doc = $_POST['documento'];
    $consulta = "SELECT id FROM estudiante WHERE documento=?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $doc);
    $stmt->execute();
    $resultado_est = $stmt->get_result();

    if ($resultado_est->num_rows > 0) {
        $row = $resultado_est->fetch_assoc();
        $estudiante_id = $row['id'];
    } else {
        // Insertar nuevo estudiante
        $stmt->close();
        $stmt = $conexion->prepare("INSERT INTO estudiante (nombre, apellido, carrera, semestre, documento) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $_POST['nombre'], $_POST['apellido'], $_POST['carrera'], $_POST['semestre'], $_POST['documento']);
        $stmt->execute();
        $estudiante_id = $stmt->insert_id;
    }
    $stmt->close();

    // Preparar datos para Flask
    $data = array(
        "Tuition fees up to date" => (int)$_POST['Tuition_fees_up_to_date'],
        "International" => (int)$_POST['International'],
        "Curricular units 2nd sem (approved)" => (int)$_POST['CU2_ap'],
        "Curricular units 2nd sem (enrolled)" => (int)$_POST['CU2_en'],
        "Debtor" => (int)$_POST['Debtor'],
        "Scholarship holder" => (int)$_POST['Scholarship_holder'],
        "Curricular units 1st sem (approved)" => (int)$_POST['CU1_ap'],
        "Displaced" => (int)$_POST['Displaced']
    );

    $json = json_encode($data);

    // Enviar a Flask
    $curl = curl_init('http://127.0.0.1:5000/predecir');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $respuesta = curl_exec($curl);

    if(curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    }
    curl_close($curl);

   

    $decoded = json_decode($respuesta, true);

   

    if ($decoded && isset($decoded['resultado'])) {
        $resultado = $decoded['resultado'];
    } else {
        $resultado = null;
        echo "<p>Error: No se obtuvo resultado de predicción válido.</p>";
    }

    // Insertar datos en formulario
    $stmt = $conexion->prepare("INSERT INTO formulario (tuiton_fees_up_to_date, international, curricular_units_2nd_str_approved, curricular_units_2nd_str_enrolled, debtor, scholarship_holder, curricular_units_1st_str_approved, displaced, fecha, estudiante_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("iiiiiiiii",
        $data["Tuition fees up to date"],
        $data["International"],
        $data["Curricular units 2nd sem (approved)"],
        $data["Curricular units 2nd sem (enrolled)"],
        $data["Debtor"],
        $data["Scholarship holder"],
        $data["Curricular units 1st sem (approved)"],
        $data["Displaced"],
        $estudiante_id
    );
    $stmt->execute();
    $formulario_id = $stmt->insert_id;
    $stmt->close();

    // Insertar alerta solo si hay resultado válido
    if ($resultado !== null) {
        $stmt = $conexion->prepare("INSERT INTO alerta (formulario_id, resultado_prediccion) VALUES (?, ?)");
        $stmt->bind_param("ii", $formulario_id, $resultado);
        $stmt->execute();
        $stmt->close();

        echo "<h3>Resultado: " . ($resultado == 1 ? "En riesgo de desertar 😢" : "No está en riesgo de desertar 😊") . "</h3>";
    } else {
        echo "<p>No se pudo guardar la alerta porque no hay resultado válido.</p>";
    }
}
?>

</body>
</html>
