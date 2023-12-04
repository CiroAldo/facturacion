<?php
ob_start(); // Iniciar el buffering de salida
require_once('../ORACLE/TCPDF-main/tcpdf.php'); // Asegúrate de que la ruta a tcpdf.php es correcta

$usuario = 'SYSTEM';
$contrasena = '12345678';
$base_datos = 'localhost/XE';

// Establecer la conexión con la base de datos
$conn = oci_connect($usuario, $contrasena, $base_datos);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$facturaID = $_POST['facturaID'] ?? ''; // Usar el operador de fusión de null para una comprobación más limpia

if ($facturaID) {
    mostrarFactura($conn, $facturaID);
}

oci_close($conn); // Cerrar la conexión
ob_end_flush(); // Enviar el buffer de salida y desactivarlo

function mostrarFactura($conn, $facturaID) {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

   // Crear un nuevo documento PDF
   $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

   $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

   // Configuraciones del documento
   $pdf->SetCreator(PDF_CREATOR);
   $pdf->SetAuthor('Nombre de tu empresa');
   $pdf->SetTitle('Factura');
   $pdf->SetSubject('Detalle de Factura');
   $pdf->SetKeywords('TCPDF, PDF, factura');

   // Establecer márgenes
   $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
   $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
   $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

   // Añadir una página
   $pdf->AddPage();

   // Consulta SQL
   $query = "SELECT 
               f.FacturaID,
               f.Fecha,
               f.Subtotal,
               f.Impuestos,
               f.Total,
               f.Estado,
               c.Nombre AS NombreCliente,
               c.Apellido AS ApellidoCliente,
               c.RFC,
               c.Direccion,
               c.Ciudad,
               c.Estado AS EstadoCliente,
               c.CodigoPostal,
               c.Telefono,
               c.Email AS EmailCliente,
               u.NombreUsuario,
               u.Nombre AS NombreUsuario,
               u.Apellido AS ApellidoUsuario,
               u.Rol,
               u.Email AS EmailUsuario,
               df.DetalleID,
               df.Cantidad,
               df.PrecioUnitario AS PrecioUnitarioDetalle,
               df.Total AS TotalDetalle,
               p.Nombre AS NombreProducto,
               p.Descripcion AS DescripcionProducto,
               p.PrecioUnitario,
               p.Stock,
               p.CodigoBarras,
               cat.Nombre AS NombreCategoria,
               cat.Descripcion AS DescripcionCategoria
           FROM 
               Facturas f
               JOIN Clientes c ON f.ClienteID = c.ClienteID
               JOIN Usuarios u ON f.UsuarioID = u.UsuarioID
               JOIN DetallesFactura df ON f.FacturaID = df.FacturaID
               JOIN Productos p ON df.ProductoID = p.ProductoID
               JOIN Categorias cat ON p.CategoriaID = cat.CategoriaID
           WHERE 
               f.FacturaID = :FacturaID";


    $query = "SELECT * FROM facturas WHERE id = :FacturaID"; // Asegúrate de cambiar 'facturas' por el nombre real de tu tabla

    // Preparar y ejecutar la consulta
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':FacturaID', $facturaID);
    oci_execute($stid);

    // Construir el HTML
    $html = '<h1>Detalle de Factura</h1>';
    $html .= '<table border="1">';
    $html .= '<tr><th>ID</th><th>Fecha</th><th>Total</th><th>Cliente</th></tr>';

    while ($fila = oci_fetch_assoc($stid)) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($fila['ID']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['FECHA']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['TOTAL']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['NOMBRE_CLIENTE']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Imprimir el HTML en el PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Cerrar y generar el documento PDF
    $pdf->Output('factura_'.$facturaID.'.pdf', 'I');
}
?>