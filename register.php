<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $host = 'localhost';
    $db = 'costos';
    $user = 'root';  // Reemplaza 'tu_usuario' con tu usuario real
    $pass = '';  // Reemplaza 'tu_contraseña' con tu contraseña real

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('INSERT INTO usuarios (username, password) VALUES (:username, :password)');
        $stmt->execute(['username' => $username, 'password' => $password]);

        echo 'Usuario creado exitosamente. <a href="login.php">Iniciar Sesión</a>';
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="post" action="register.php">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Crear Usuario</button>
    </form>
</body>
</html>
