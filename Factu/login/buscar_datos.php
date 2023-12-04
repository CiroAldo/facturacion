<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados de Búsqueda</title>
</head>

<body>
    <h1>Resultados de Búsqueda</h1>
    <?php
    // Datos de conexión a la base de datos Oracle
    $dbuser = 'C##LOGIN';
    $dbpass = '2003';
    $dbname = '//localhost/XE'; // Cambia 'localhost' y 'XE' según tu configuración de Oracle

    // Establecer la conexión con Oracle
    $conn = oci_connect($dbuser, $dbpass, $dbname);

    // Verificar si la conexión fue exitosa
    if (!$conn) {
        $e = oci_error();
        die("Conexión fallida: " . $e['message']);
    }

    if ($numRows > 0) {
        // Mostrar los registros encontrados
        foreach ($results as $row) {
            echo "ID Usuario: " . $row['ID_USUARIO'] . "<br>";
            echo "Nombre de Usuario: " . $row['NOMBRE_USUARIO'] . "<br>";
            echo "Contraseña: " . $row['CONTRASENIS'] . "<br>";
            echo "Correo: " . $row['CORREO'] . "<br><br>";
        }
    } else {
        echo "No se encontraron registros para este correo electrónico.";
    }

    // Cerrar la conexión
    oci_close($conn);

    // Crear conexión adicional con Oracle
    $conexion = oci_connect("C##LOGIN", "2003", "localhost/xe");

    if (!$conexion) {
        $m = oci_error();
        echo $m['message'], "n";
        exit;
    } else {
        echo "Conexión exitosa a Oracle!";
    }
    ?>
</body>

</html>
