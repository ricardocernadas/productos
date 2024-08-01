<?php
// Inicia la sesión solo si aún no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db = 'costos';
$user = 'root';  // Reemplaza 'tu_usuario' con tu usuario real
$pass = '';  // Reemplaza 'tu_contraseña' con tu contraseña real

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
}
?>
