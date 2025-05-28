<?php
session_start();

if (!isset($_SESSION['administrativo_id'])) {
    header('Location: login.php');
    exit();
}

$nombre = $_SESSION['administrativo_nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Administrativo</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($nombre); ?></h1>

    <form action="listar_formularios.php" method="get">
        <button type="submit">Ver listado de formularios y predicciones</button>
    </form>

    <form action="logout.php" method="get">
        <button type="submit">Cerrar sesión</button>
    </form>
</body>
</html>
