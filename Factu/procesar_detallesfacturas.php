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

function mostrarDetallesFactura($conn) {
    $query = "SELECT * FROM DetallesFactura";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    echo "<table border='1'>";
    echo "<tr><th>DetalleID</th><th>FacturaID</th><th>ProductoID</th><th>Cantidad</th><th>Precio Unitario</th><th>Total</th></tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['DETALLEID'] . "</td>";
        echo "<td>" . $row['FACTURAID'] . "</td>";
        echo "<td>" . $row['PRODUCTOID'] . "</td>";
        echo "<td>" . $row['CANTIDAD'] . "</td>";
        echo "<td>" . $row['PRECIOUNITARIO'] . "</td>";
        echo "<td>" . $row['TOTAL'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

if ($action == "insert") {
    $facturaID = $_POST['facturaID'];
    $productoID = $_POST['productoID'];
    $cantidad = $_POST['cantidad'];
    $precioUnitario = $_POST['precioUnitario'];
    $total = $_POST['total'];

    $query = "INSERT INTO DetallesFactura (FacturaID, ProductoID, Cantidad, PrecioUnitario, Total) VALUES (:facturaID, :productoID, :cantidad, :precioUnitario, :total)";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':facturaID', $facturaID);
    oci_bind_by_name($stid, ':productoID', $productoID);
    oci_bind_by_name($stid, ':cantidad', $cantidad);
    oci_bind_by_name($stid, ':precioUnitario', $precioUnitario);
    oci_bind_by_name($stid, ':total', $total);

    if (oci_execute($stid)) {
        echo "Detalle de factura agregado con éxito!";
    } else {
        echo "Error al agregar el detalle de factura.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "update") {
    $detalleID = $_POST['detalleID'];
    $facturaID = $_POST['facturaID'];
    $productoID = $_POST['productoID'];
    $cantidad = $_POST['cantidad'];
    $precioUnitario = $_POST['precioUnitario'];
    $total = $_POST['total'];

    $query = "UPDATE DetallesFactura SET FacturaID = :facturaID, ProductoID = :productoID, Cantidad = :cantidad, PrecioUnitario = :precioUnitario, Total = :total WHERE DetalleID = :detalleID";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':detalleID', $detalleID);
    oci_bind_by_name($stid, ':facturaID', $facturaID);
    oci_bind_by_name($stid, ':productoID', $productoID);
    oci_bind_by_name($stid, ':cantidad', $cantidad);
    oci_bind_by_name($stid, ':precioUnitario', $precioUnitario);
    oci_bind_by_name($stid, ':total', $total);

    if (oci_execute($stid)) {
        echo "Detalle de factura actualizado con éxito!";
    } else {
        echo "Error al actualizar el detalle de factura.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "delete") {
    $detalleID = $_POST['detalleID'];

    $query = "DELETE FROM DetallesFactura WHERE DetalleID = :detalleID";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':detalleID', $detalleID);

    if (oci_execute($stid)) {
        echo "Detalle de factura eliminado con éxito!";
    } else {
        echo "Error al eliminar el detalle de factura.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
}

oci_close($conn);

// Mostrar la tabla de detalles de factura después de cualquier acción
mostrarDetallesFactura($conn);
?>
