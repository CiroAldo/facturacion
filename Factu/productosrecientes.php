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

// Consulta para obtener el total de productos en stock
$queryTotalProductos = "SELECT SUM(STOCK) AS TOTAL_STOCK FROM PRODUCTOS";
$stidTotalProductos = oci_parse($conn, $queryTotalProductos);
oci_execute($stidTotalProductos);
$totalProductosRow = oci_fetch_array($stidTotalProductos, OCI_ASSOC);
$totalStock = $totalProductosRow['TOTAL_STOCK']; // Aquí se corrige la asignación correcta de la variable

// Empezar a construir el HTML para el total de productos
$htmlTotalProductos = "<h2>Total de Productos en Stock: {$totalStock}</h2>";

$queryPocoStock = "SELECT * FROM Productos WHERE STOCK <= 30 ORDER BY STOCK ASC";
$stidPocoStock = oci_parse($conn, $queryPocoStock);
oci_execute($stidPocoStock);

// Empezar a construir el HTML para los productos con poco stock
$htmlPocoStock = '<h2>Productos con Poco Stock</h2>';
while ($producto = oci_fetch_assoc($stidPocoStock)) {
    $htmlPocoStock .= "
        <div class='card'>
            <div class='card-content'>
                <span class='card-title'>Producto: {$producto['NOMBRE']}</span>
                <p>Precio Unitario: {$producto['PRECIOUNITARIO']}</p>
                <p>Stock: {$producto['STOCK']}</p>
            </div>
        </div>
    ";
}

oci_free_statement($stidTotalProductos);
oci_free_statement($stidPocoStock);
oci_close($conn);

// Imprimir el HTML
echo $htmlTotalProductos;
echo $htmlPocoStock;
?>