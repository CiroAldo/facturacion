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

function mostrarFacturas($conn) {
    $query = "SELECT * FROM Facturas";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    echo "<table border='1'>";
    echo "<tr><th>ID Factura</th><th>ID Cliente</th><th>Fecha</th><th>Subtotal</th><th>Impuestos</th><th>Total</th><th>Estado</th></tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['FACTURAID'] . "</td>";
        echo "<td>" . $row['CLIENTEID'] . "</td>";
        echo "<td>" . $row['FECHA'] . "</td>";
        echo "<td>" . $row['SUBTOTAL'] . "</td>";
        echo "<td>" . $row['IMPUESTOS'] . "</td>";
        echo "<td>" . $row['TOTAL'] . "</td>";
        echo "<td>" . $row['ESTADO'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

if ($action == "insert") {
    $clienteID = $_POST['clienteID'];
    $subtotal = $_POST['subtotal'];
    $impuestos = $_POST['impuestos'];
    $total = $_POST['total'];
    $estado = $_POST['estado'];

    $query = "INSERT INTO Facturas (ClienteID, Subtotal, Impuestos, Total, Estado) VALUES (:clienteID, :subtotal, :impuestos, :total, :estado)";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':clienteID', $clienteID);
    oci_bind_by_name($stid, ':subtotal', $subtotal);
    oci_bind_by_name($stid, ':impuestos', $impuestos);
    oci_bind_by_name($stid, ':total', $total);
    oci_bind_by_name($stid, ':estado', $estado);

    if (oci_execute($stid)) {
        echo "Factura agregada con éxito!";
    } else {
        echo "Error al agregar la factura.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "update") {
    $facturaID = $_POST['facturaID'];
    $clienteID = $_POST['clienteID'];
    $subtotal = $_POST['subtotal'];
    $impuestos = $_POST['impuestos'];
    $total = $_POST['total'];
    $estado = $_POST['estado'];

    $query = "UPDATE Facturas SET ClienteID = :clienteID, Subtotal = :subtotal, Impuestos = :impuestos, Total = :total, Estado = :estado WHERE FacturaID = :facturaID";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':facturaID', $facturaID);
    oci_bind_by_name($stid, ':clienteID', $clienteID);
    oci_bind_by_name($stid, ':subtotal', $subtotal);
    oci_bind_by_name($stid, ':impuestos', $impuestos);
    oci_bind_by_name($stid, ':total', $total);
    oci_bind_by_name($stid, ':estado', $estado);

    if (oci_execute($stid)) {
        echo "Factura actualizada con éxito!";
    } else {
        echo "Error al actualizar la factura.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "delete") {
    $facturaID = $_POST['facturaID'];

    $query = "DELETE FROM Facturas WHERE FacturaID = :facturaID";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':facturaID', $facturaID);

    if (oci_execute($stid)) {
        echo "Factura eliminada con éxito!";
    } else {
        echo "Error al eliminar la factura.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
}

oci_close($conn);

// Mostrar la tabla de facturas después de cualquier acción
mostrarFacturas($conn);
?>
