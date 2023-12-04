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

// Función para mostrar la cantidad total de productos comprados por cliente
function mostrarTotalProductosPorCliente($conn) {
    $query = "SELECT cl.Nombre, cl.Apellido, p.Nombre AS Producto, SUM(df.Cantidad) AS TotalCantidad
              FROM Clientes cl
              JOIN Facturas f ON cl.ClienteID = f.ClienteID
              JOIN DetallesFactura df ON f.FacturaID = df.FacturaID
              JOIN Productos p ON df.ProductoID = p.ProductoID
              GROUP BY cl.Nombre, cl.Apellido, p.Nombre
              ORDER BY TotalCantidad DESC";

    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    // Imprimir una tabla HTML con los datos de la consulta
    echo "<table border='1'>";
    echo "<tr>
            <th>Nombre Cliente</th>
            <th>Apellido Cliente</th>
            <th>Producto</th>
            <th>Total Cantidad</th>
          </tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['NOMBRE'] . "</td>";
        echo "<td>" . $row['APELLIDO'] . "</td>";
        echo "<td>" . $row['PRODUCTO'] . "</td>";
        echo "<td>" . $row['TOTALCANTIDAD'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Llamar a la función para mostrar la cantidad total de productos por cliente
mostrarTotalProductosPorCliente($conn);

// Cerrar la conexión con la base de datos
oci_close($conn);
?>
