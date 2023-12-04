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

// Función para mostrar las facturas con detalles de productos
function mostrarDetallesFactura($conn) {
    $query = "SELECT f.FacturaID, p.Nombre AS Producto, df.Cantidad, df.PrecioUnitario, df.Total
              FROM DetallesFactura df
              JOIN Facturas f ON df.FacturaID = f.FacturaID
              JOIN Productos p ON df.ProductoID = p.ProductoID";

    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    // Imprimir una tabla HTML con los datos de las facturas y detalles de productos
    echo "<table border='1'>";
    echo "<tr>
            <th>FacturaID</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Total</th>
          </tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['FACTURAID'] . "</td>";
        echo "<td>" . $row['PRODUCTO'] . "</td>";
        echo "<td>" . $row['CANTIDAD'] . "</td>";
        echo "<td>" . $row['PRECIOUNITARIO'] . "</td>";
        echo "<td>" . $row['TOTAL'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Llamar a la función para mostrar las facturas con detalles de productos
mostrarDetallesFactura($conn);

// Cerrar la conexión con la base de datos
oci_close($conn);
?>
