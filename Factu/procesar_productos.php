<?php
$usuario = 'SYSTEM';
$contrasena = '12345678';
$base_datos = 'localhost/XE';

$conn = oci_connect($usuario, $contrasena, $base_datos);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$action = $_POST['action'] ?? '';

function mostrarProductos($conn) {
    $query = "SELECT * FROM Productos";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    echo "<table border='1'>";
    echo "<tr><th>ID Producto</th><th>Categoría ID</th><th>Nombre</th><th>Descripción</th><th>Precio Unitario</th><th>Stock</th><th>Código de Barras</th></tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['PRODUCTOID'] . "</td>";
        echo "<td>" . $row['CATEGORIAID'] . "</td>";
        echo "<td>" . $row['NOMBRE'] . "</td>";
        echo "<td>" . $row['DESCRIPCION'] . "</td>";
        echo "<td>" . $row['PRECIOUNITARIO'] . "</td>";
        echo "<td>" . $row['STOCK'] . "</td>";
        echo "<td>" . $row['CODIGOBARRAS'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

if ($action == "insert") {
    $categoriaID = $_POST['categoriaID'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precioUnitario = $_POST['precioUnitario'];
    $stock = $_POST['stock'];
    $codigoBarras = $_POST['codigoBarras'];

    $query = "INSERT INTO Productos (CategoriaID, Nombre, Descripcion, PrecioUnitario, Stock, CodigoBarras) VALUES (:categoriaID, :nombre, :descripcion, :precioUnitario, :stock, :codigoBarras)";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':categoriaID', $categoriaID);
    oci_bind_by_name($stid, ':nombre', $nombre);
    oci_bind_by_name($stid, ':descripcion', $descripcion);
    oci_bind_by_name($stid, ':precioUnitario', $precioUnitario);
    oci_bind_by_name($stid, ':stock', $stock);
    oci_bind_by_name($stid, ':codigoBarras', $codigoBarras);

    if (oci_execute($stid)) {
        echo "Producto agregado con éxito!";
    } else {
        echo "Error al agregar el producto.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "update") {
    $productoID = $_POST['productoID'];
    $categoriaID = $_POST['categoriaID'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precioUnitario = $_POST['precioUnitario'];
    $stock = $_POST['stock'];
    $codigoBarras = $_POST['codigoBarras'];

    $query = "UPDATE Productos SET CategoriaID = :categoriaID, Nombre = :nombre, Descripcion = :descripcion, PrecioUnitario = :precioUnitario, Stock = :stock, CodigoBarras = :codigoBarras WHERE ProductoID = :productoID";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':productoID', $productoID);
    oci_bind_by_name($stid, ':categoriaID', $categoriaID);
    oci_bind_by_name($stid, ':nombre', $nombre);
    oci_bind_by_name($stid, ':descripcion', $descripcion);
    oci_bind_by_name($stid, ':precioUnitario', $precioUnitario);
    oci_bind_by_name($stid, ':stock', $stock);
    oci_bind_by_name($stid, ':codigoBarras', $codigoBarras);

    if (oci_execute($stid)) {
        echo "Producto actualizado con éxito!";
    } else {
        echo "Error al actualizar el producto.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "delete") {
    $productoID = $_POST['productoID'];

    $query = "DELETE FROM Productos WHERE ProductoID = :productoID";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':productoID', $productoID);

    if (oci_execute($stid)) {
        echo "Producto eliminado con éxito!";
    } else {
        echo "Error al eliminar el producto.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
}

oci_close($conn);

// Mostrar la tabla de productos después de cualquier acción
mostrarProductos($conn);
?>
