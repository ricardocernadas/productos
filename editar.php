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
    die('ID del producto no válido.');
}

$id = (int)$_GET['id'];

// Obtener los datos del producto para mostrar en el formulario
$stmt = $pdo->prepare('SELECT id_producto, nombre, precio_compra, precio_final, id_categoria FROM productos WHERE id_producto = :id');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die('Producto no encontrado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $precio_compra = $_POST['precio_compra'] ?? '';
    $precio_final = $_POST['precio_final'] ?? '';
    $id_categoria = $_POST['categoria'] ?? '';

    // Calcular el IVA y el precio final
    $iva = round($precio_compra * 0.21, 2);
    $precio_final = round($precio_compra + $iva, 2);

    // Actualizar el producto en la base de datos
    $stmt = $pdo->prepare('
        UPDATE productos
        SET nombre = :nombre, precio_compra = :precio_compra, precio_final = :precio_final, id_categoria = :id_categoria
        WHERE id_producto = :id
    ');

    if ($stmt->execute([
        'nombre' => $nombre,
        'precio_compra' => $precio_compra,
        'precio_final' => $precio_final,
        'id_categoria' => $id_categoria,
        'id' => $id
    ])) {
        header('Location: index.php');
        exit;
    } else {
        echo 'Error al actualizar el producto.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Editar Producto</h1>
    <form method="post">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($product['nombre']); ?>" required>
        <br>
        <label for="precio_compra">Precio de Compra:</label>
        <input type="text" id="precio_compra" name="precio_compra" value="<?php echo htmlspecialchars($product['precio_compra']); ?>" required>
        <br>
        <label for="precio_final">Precio Final:</label>
        <input type="text" id="precio_final" name="precio_final" value="<?php echo htmlspecialchars($product['precio_final']); ?>" required>
        <br>
        <label for="categoria">Categoría:</label>
        <select id="categoria" name="categoria" required>
            <?php
            // Obtener las categorías de la base de datos
            $stmt = $pdo->query('SELECT categoria_id, categoria_nombre FROM categorias');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $selected = $row['categoria_id'] == $product['id_categoria'] ? 'selected' : '';
                echo '<option value="' . $row['categoria_id'] . '" ' . $selected . '>' . $row['categoria_nombre'] . '</option>';
            }
            ?>
        </select>
        <br>
        <button type="submit">Actualizar Producto</button>
    </form>
    <a href="index.php">Volver a la lista de productos</a>
</body>
</html>
