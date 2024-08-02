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

// Obtener los productos para mostrar en la tabla
$stmt = $pdo->query('
    SELECT p.id_producto, p.nombre, p.precio_compra, p.precio_final, c.categoria_nombre
    FROM productos p
    JOIN categorias c ON p.id_categoria = c.categoria_id
');

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $precio_compra = $_POST['precio_compra'] ?? '';
    $id_categoria = $_POST['categoria'] ?? '';

    // Validación básica
    if (empty($nombre) || !is_numeric($precio_compra) || empty($id_categoria)) {
        echo 'Por favor, complete todos los campos correctamente.';
        exit;
    }

    // Calcular el IVA y el precio final
    $iva = round($precio_compra * 0.21, 2);
    $precio_final = round($precio_compra + $iva, 2);

    // Insertar el nuevo producto
    $stmt = $pdo->prepare('
        INSERT INTO productos (nombre, precio_compra, precio_final, id_categoria)
        VALUES (:nombre, :precio_compra, :precio_final, :id_categoria)
    ');

    if ($stmt->execute([
        'nombre' => $nombre,
        'precio_compra' => $precio_compra,
        'precio_final' => $precio_final,
        'id_categoria' => $id_categoria
    ])) {
        header('Location: index.php');
        exit;
    } else {
        echo 'Error al agregar el producto.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Productos</title>
    
    
</head>
<body>
    <header>
        <nav class= "navbar">
            <div class="navbar-izq"> 
                <a href="index.php">Inicio</a>    
                <a href="nuevo.php">Nuevo Artículo</a>
                <a href="lista.php">Lista de Productos</a>
                <a href="costos.php">Costos</a>
                <a href="listasPrecios.php">Lista de Precios</a>
            </div>
            <div class="navbar-der">
                <a href="logout.php">Cerrar Sesión</a>
            </div>
        </nav>
        
    </header>
    <h1>Productos</h1>

    <!-- Formulario para agregar un nuevo producto -->
    <h2>Agregar Producto</h2>
    <form method="post">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br>
        <label for="precio_compra">Precio de Compra:</label>
        <input type="text" id="precio_compra" name="precio_compra" required>
        <br>
        <label for="categoria">Categoría:</label>
        <select id="categoria" name="categoria" required>
            <?php
            // Obtener las categorías de la base de datos
            $stmt = $pdo->query('SELECT categoria_id, categoria_nombre FROM categorias');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['categoria_id'] . '">' . $row['categoria_nombre'] . '</option>';
            }
            ?>
        </select>
        <br>
        <button type="submit" name="add_product">Agregar Producto</button>
    </form>

