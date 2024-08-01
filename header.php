<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Aplicación</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <!-- Agrega aquí más enlaces de navegación si es necesario -->
                <li style="float: right;"><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
