<?php
require 'conexion.php'; // Incluye el archivo de conexión

// Verifica si la sesión ya está iniciada antes de llamarla de nuevo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirige a la página de login si el usuario no está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Verifica si se ha enviado un ID a través de la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Solicitud no válida.');
}

$id = (int)$_GET['id'];

// Preparar y ejecutar la eliminación del producto
$stmt = $pdo->prepare('DELETE FROM productos WHERE id_producto = :id');
if ($stmt->execute(['id' => $id])) {
    header('Location: index.php');
    exit;
} else {
    echo 'Error al eliminar el producto.';
}
?>
