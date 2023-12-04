

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $conexion = oci_connect("C##LOGIN", "2003", "localhost/xe");

    if (!$conexion) {
        $m = oci_error();
        echo $m['message'], "\n";
        exit;
    }

    $consulta = oci_parse($conexion, "SELECT * FROM usuario WHERE nombre_usuario = :nombre_usuario");
    oci_bind_by_name($consulta, ':nombre_usuario', $username);
    oci_execute($consulta);

    $fila = oci_fetch_assoc($consulta);
    if ($fila && $fila['CONTRASENIS'] === $password) {
        echo "¡Bienvenido, " . $fila['NOMBRE_USUARIO'] . "!";
        // Aquí podrías redirigir a una página de bienvenida o realizar alguna acción adicional
    } else {
        echo "Credenciales incorrectas. Por favor, regístrate.";
        echo '<a href="registro.php"><button>Registrarse</button></a>';
    }

    oci_close($conexion);
}
?>

