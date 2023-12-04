<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf8">
    <title>Resultado de Guardado</title>
</head>
<body>
<?php
// Crear conexión con Oracle
$conexion = oci_connect("C##LOGIN", "2003", "localhost/xe");

if (!$conexion) {
    $m = oci_error();
    echo $m['message'], "<br>";
    exit;
}

// Inicializar mensaje
$mensaje = '';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreUsuario = $_POST['username'];
    $contrasenia = $_POST['password'];
    $correo = $_POST['email'];


    // Preparar la consulta SQL para la inserción
    $sql = "INSERT INTO usuario (nombre_usuario, contrasenis, correo ) VALUES (:nombreUsuario, :contrasenia, :correo)";

    // Preparar la sentencia
    $stmt = oci_parse($conexion, $sql);

    // Vincular variables a los marcadores
    oci_bind_by_name($stmt, ':nombreUsuario', $nombreUsuario);
    oci_bind_by_name($stmt, ':contrasenia', $contrasenia);
    oci_bind_by_name($stmt, ':correo', $correo);

    // Ejecutar la sentencia
    $ejecucion = oci_execute($stmt);

    if ($ejecucion) {
        $mensaje = "Datos guardados correctamente";
    } else {
        $error = oci_error($stmt);
        $mensaje = "Error al guardar datos: " . $error['message'];
    }

    // Liberar los recursos y cerrar la conexión
    oci_free_statement($stmt);
    oci_close($conexion);
}
?>

<div>
    <p><?php echo $mensaje; ?></p>
    <form action="index.php">
        <input type="submit" value="Regresar a la página principal">
    </form>
</div>

</body>
</html>

