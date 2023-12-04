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
    // Crear un nuevo documento PDF
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

    // Consulta SQL ampliada
    $query = "SELECT 
                   f.FacturaID,
                   f.Fecha,
                   f.Subtotal,
                   f.Impuestos,
                   f.Total,
                   f.Estado,
                   c.Nombre AS NombreCliente,
                   c.Apellido AS ApellidoCliente,
                   c.Estado AS EstadoCliente,
                   c.Email AS EmailCliente,
                   u.Nombre AS NombreUsuario,
                   u.Apellido AS ApellidoUsuario,
                   u.Email AS EmailUsuario,
                   df.Cantidad,
                   df.PrecioUnitario AS PrecioUnitarioDetalle,
                   df.Total AS TotalDetalle,
                   p.Nombre AS NombreProducto,
                   p.Descripcion AS DescripcionProducto,
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

    // Preparar y ejecutar la consulta
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':FacturaID', $facturaID);
    oci_execute($stid);

    // Construir el HTML para el PDF
    $html = '<h1>Detalle de Factura</h1>';
    $html .= '<table border="1">';
    $html .= '<tr>
                  <th>ID Factura</th>
                  <th>Fecha</th>
                  <th>Subtotal</th>
                  <th>Impuestos</th>
                  <th>Total</th>
                  <th>Estado</th>
                  <th>Cliente</th>
                  <th>Email Cliente</th>
                  <th>Usuario</th>
                  <th>Email Usuario</th>
                  <th>Cantidad</th>
                  <th>Precio Unitario Detalle</th>
                  <th>Total Detalle</th>
                  <th>Producto</th>
                  <th>Descripción Producto</th>
                  <th>Categoría</th>
                  <th>Descripción Categoría</th>
              </tr>';

    while ($fila = oci_fetch_assoc($stid)) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($fila['FACTURAID']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['FECHA']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['SUBTOTAL']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['IMPUESTOS']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['TOTAL']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['ESTADO']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['NOMBRECLIENTE']) . ' ' . htmlspecialchars($fila['APELLIDOCLIENTE']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['EMAILCLIENTE']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['NOMBREUSUARIO']) . ' ' . htmlspecialchars($fila['APELLIDOUSUARIO']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['EMAILUSUARIO']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['CANTIDAD']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['PRECIOUNITARIODETALLE']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['TOTALDETALLE']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['NOMBREPRODUCTO']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['DESCRIPCIONPRODUCTO']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['NOMBRECATEGORIA']) . '</td>';
        $html .= '<td>' . htmlspecialchars($fila['DESCRIPCIONCATEGORIA']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Imprimir el HTML en el PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Cerrar y generar el documento PDF
    $pdf->Output('factura_'.$facturaID.'.pdf', 'I');
}
?>