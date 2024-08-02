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

// Obtener el término de búsqueda si existe
$searchTerm = $_GET['search'] ?? '';

// Obtener los productos según el término de búsqueda
$sql = '
    SELECT p.id_producto, p.nombre, p.precio_compra, p.precio_final, c.categoria_nombre
    FROM productos p
    JOIN categorias c ON p.id_categoria = c.categoria_id
';

if ($searchTerm) {
    $sql .= ' WHERE p.nombre LIKE :searchTerm OR c.categoria_nombre LIKE :searchTerm';
}

$stmt = $pdo->prepare($sql);

if ($searchTerm) {
    $stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
} else {
    $stmt->execute();
}

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<body> <h1>Lista de Productos</h1>

<!-- Formulario de búsqueda -->
<form method="get" action="lista.php">
    <input type="text" name="search" placeholder="Buscar productos" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Buscar</button>
</form>

<!-- Mostrar la tabla de productos -->
<h2>Productos</h2>
<table>
    <thead>
        <tr>
            <th>Nombre del Producto</th>
            <th>Categoría</th>
            <th>Precio de Compra</th>
            <th>Precio Final</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                <td><?php echo '$' . number_format($producto['precio_compra'], 2); ?></td>
                <td><?php echo '$' . number_format($producto['precio_final'], 2); ?></td>
                <td>
                    <a href="editar.php?id=<?php echo urlencode($producto['id_producto']); ?>">✏️</a>
                    <a href="eliminar.php?id=<?php echo urlencode($producto['id_producto']); ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">❌</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


</body>