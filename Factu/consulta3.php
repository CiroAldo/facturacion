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

// Función para mostrar todas las facturas con información detallada del cliente
function mostrarFacturasConDetalleClientes($conn) {
    $query = "SELECT f.FacturaID, cl.Nombre, cl.Apellido, f.Fecha, f.Total
              FROM Facturas f
              JOIN Clientes cl ON f.ClienteID = cl.ClienteID";

    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    // Imprimir una tabla HTML con los datos de las facturas y los clientes
    echo "<table border='1'>";
    echo "<tr>
            <th>FacturaID</th>
            <th>Nombre Cliente</th>
            <th>Apellido Cliente</th>
            <th>Fecha</th>
            <th>Total</th>
          </tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['FACTURAID'] . "</td>";
        echo "<td>" . $row['NOMBRE'] . "</td>";
        echo "<td>" . $row['APELLIDO'] . "</td>";
        echo "<td>" . $row['FECHA'] . "</td>";
        echo "<td>" . $row['TOTAL'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Llamar a la función para mostrar las facturas con detalle de clientes
mostrarFacturasConDetalleClientes($conn);

// Cerrar la conexión con la base de datos
oci_close($conn);
?>
