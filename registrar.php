<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($username) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "<p>Usuario registrado exitosamente. <a href='login.php'>Iniciar sesión</a></p>";
        } else {
            echo "<p>Error al registrar el usuario.</p>";
        }
    } else {
        echo "<p>Por favor, complete todos los campos.</p>";
    }
}
?>
