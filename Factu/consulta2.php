<?php
$usuario = 'SYSTEM';
$contrasena = '12345678';
$base_datos = 'localhost/XE';

// Establecer la conexión con la base de datos
$conn = oci_connect($usuario, $contrasena, $base_datos);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Función para mostrar los productos y sus categorías
function mostrarProductosPorCategoria($conn) {
    $query = "SELECT p.ProductoID, p.Nombre, c.Nombre AS Categoria, p.PrecioUnitario, p.Stock
              FROM Productos p
              JOIN Categorias c ON p.CategoriaID = c.CategoriaID";

    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    // Imprimir una tabla HTML con los datos de los productos
    echo "<table border='1'>";
    echo "<tr>
            <th>ProductoID</th>
            <th>Nombre</th>
            <th>Categoria</th>
            <th>Precio Unitario</th>
            <th>Stock</th>
          </tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['PRODUCTOID'] . "</td>";
        echo "<td>" . $row['NOMBRE'] . "</td>";
        echo "<td>" . $row['CATEGORIA'] . "</td>";
        echo "<td>" . $row['PRECIOUNITARIO'] . "</td>";
        echo "<td>" . $row['STOCK'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Llamar a la función para mostrar los productos por categoría
mostrarProductosPorCategoria($conn);

// Cerrar la conexión con la base de datos
oci_close($conn);
?>
