<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


include 'conexion.php';

// Obtener los datos del formulario
$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$precio_compra = isset($_POST['precio_compra']) ? (float)$_POST['precio_compra'] : 0;
$iva = round($precio_compra * 0.21, 2);
$precio_final = round($precio_compra + $iva, 2);
$id_categoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;

if ($id_producto > 0 && !empty($nombre) && $precio_compra >= 0 && $id_categoria > 0) {
    // Actualizar los datos del producto en la base de datos
    $sql = "UPDATE productos SET nombre = :nombre, precio_compra = :precio_compra, iva = :iva, precio_final = :precio_final, id_categoria = :id_categoria WHERE id_producto = :id_producto";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':precio_compra', $precio_compra, PDO::PARAM_STR);
    $stmt->bindParam(':iva', $iva, PDO::PARAM_STR);
    $stmt->bindParam(':precio_final', $precio_final, PDO::PARAM_STR);
    $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        echo "<p>Error al actualizar el producto.</p>";
    }
} else {
    echo "<p>Todos los campos son obligatorios.</p>";
}
?>
