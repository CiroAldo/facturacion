<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$usuario = 'SYSTEM';
$contrasena = '12345678'; // Asegúrate de usar la contraseña correcta para tu entorno
$base_datos = 'localhost/XE';

$conn = oci_connect($usuario, $contrasena, $base_datos);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$query = "
    SELECT f.FacturaID, f.Total, f.Estado, c.Nombre, c.Apellido 
    FROM Facturas f
    JOIN Clientes c ON f.ClienteID = c.ClienteID
    ORDER BY f.Fecha DESC
    FETCH NEXT 5 ROWS ONLY
";

$stid = oci_parse($conn, $query);
oci_execute($stid);

// Empezar a construir el HTML
$html = '<h2>Facturas Recientes</h2>';

while ($row = oci_fetch_assoc($stid)) {
    $nombreCompleto = $row['NOMBRE'] . ' ' . $row['APELLIDO'];
    $html .= "
        <div class='card'>
            <div class='card-content'>
                <span class='card-title'>Factura #{$row['FACTURAID']}</span>
                <p>Cliente: {$nombreCompleto}</p>
                <p>Total: {$row['TOTAL']}</p>
                <p>Estado: {$row['ESTADO']}</p>
            </div>
        </div>
    ";
}

oci_close($conn);

// Imprimir el HTML
echo $html;
?>
