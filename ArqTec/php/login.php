<?php
session_start();

// Parámetros de conexión
$host = "localhost";
$dbname = "mydb";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $conn->real_escape_string($_POST['correo']);
    $contrasena = $conn->real_escape_string($_POST['contrasena']);

    $sql = "SELECT * FROM administrativo WHERE correo = '$correo' AND contrasena = '$contrasena' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        $_SESSION['administrativo_id'] = $admin['id'];
        $_SESSION['administrativo_nombre'] = $admin['nombre'];

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Correo o contraseña incorrectos.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Login Administrativo</title>
</head>
<body>
    <h2>Login Administrativo</h2>
    <form method="post" action="login.php">
        <label for="correo">Correo:</label><br>
        <input type="email" id="correo" name="correo" required><br><br>
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br><br>
        <input type="submit" value="Ingresar">
    </form>
    <form action="index.php" method="get">
        <button type="submit">Regresar</button>
    </form>
</body>
</html>
