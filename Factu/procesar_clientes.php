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

function mostrarClientes($conn) {
    $query = "SELECT * FROM Clientes";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    echo "<table border='1'>";
    echo "<tr><th>ID Cliente</th><th>Nombre</th><th>Apellido</th><th>RFC</th><th>Dirección</th><th>Ciudad</th><th>Estado</th><th>Código Postal</th><th>Teléfono</th><th>Email</th></tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['CLIENTEID'] . "</td>";
        echo "<td>" . $row['NOMBRE'] . "</td>";
        echo "<td>" . $row['APELLIDO'] . "</td>";
        echo "<td>" . $row['RFC'] . "</td>";
        echo "<td>" . $row['DIRECCION'] . "</td>";
        echo "<td>" . $row['CIUDAD'] . "</td>";
        echo "<td>" . $row['ESTADO'] . "</td>";
        echo "<td>" . $row['CODIGOPOSTAL'] . "</td>";
        echo "<td>" . $row['TELEFONO'] . "</td>";
        echo "<td>" . $row['EMAIL'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

if ($action == "insert") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $rfc = $_POST['rfc'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigoPostal = $_POST['codigoPostal'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    // Preparar la consulta SQL
    $query = "INSERT INTO Clientes (Nombre, Apellido, RFC, Direccion, Ciudad, Estado, CodigoPostal, Telefono, Email) VALUES (:nombre, :apellido, :rfc, :direccion, :ciudad, :estado, :codigoPostal, :telefono, :email)";
    $stid = oci_parse($conn, $query);

    // Vincular los parámetros
    oci_bind_by_name($stid, ':nombre', $nombre);
    oci_bind_by_name($stid, ':apellido', $apellido);
    oci_bind_by_name($stid, ':rfc', $rfc);
    oci_bind_by_name($stid, ':direccion', $direccion);
    oci_bind_by_name($stid, ':ciudad', $ciudad);
    oci_bind_by_name($stid, ':estado', $estado);
    oci_bind_by_name($stid, ':codigoPostal', $codigoPostal);
    oci_bind_by_name($stid, ':telefono', $telefono);
    oci_bind_by_name($stid, ':email', $email);

    // Ejecutar la consulta
    if (oci_execute($stid)) {
        echo "Cliente agregado con éxito!";
    } else {
        echo "Error al agregar el cliente.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "update") {
    // Recoger los datos del formulario
    $clienteID = $_POST['clienteID'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $rfc = $_POST['rfc'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $estado = $_POST['estado'];
    $codigoPostal = $_POST['codigoPostal'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    // Preparar la consulta SQL
    $query = "UPDATE Clientes SET Nombre = :nombre, Apellido = :apellido, RFC = :rfc, Direccion = :direccion, Ciudad = :ciudad, Estado = :estado, CodigoPostal = :codigoPostal, Telefono = :telefono, Email = :email WHERE ClienteID = :clienteID";
    $stid = oci_parse($conn, $query);

    // Vincular los parámetros
    oci_bind_by_name($stid, ':clienteID', $clienteID);
    oci_bind_by_name($stid, ':nombre', $nombre);
    oci_bind_by_name($stid, ':apellido', $apellido);
    oci_bind_by_name($stid, ':rfc', $rfc);
    oci_bind_by_name($stid, ':direccion', $direccion);
    oci_bind_by_name($stid, ':ciudad', $ciudad);
    oci_bind_by_name($stid, ':estado', $estado);
    oci_bind_by_name($stid, ':codigoPostal', $codigoPostal);
    oci_bind_by_name($stid, ':telefono', $telefono);
    oci_bind_by_name($stid, ':email', $email);

    // Ejecutar la consulta
    if (oci_execute($stid)) {
        echo "Cliente actualizado con éxito!";
    } else {
        echo "Error al actualizar el cliente.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "delete") {
    // Recoger el ID del cliente
    $clienteID = $_POST['clienteID'];

    // Preparar la consulta SQL
    $query = "DELETE FROM Clientes WHERE ClienteID = :clienteID";
    $stid = oci_parse($conn, $query);

    // Vincular el parámetro
    oci_bind_by_name($stid, ':clienteID', $clienteID);

    // Ejecutar la consulta
    if (oci_execute($stid)) {
        echo "Cliente eliminado con éxito!";
    } else {
        echo "Error al eliminar el cliente.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
}

oci_close($conn);

// Mostrar la tabla de clientes después de cualquier acción
mostrarClientes($conn);
?>
