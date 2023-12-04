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

function mostrarUsuarios($conn) {
    $query = "SELECT * FROM Usuarios";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    echo "<table border='1'>";
    echo "<tr><th>ID Usuario</th><th>Nombre de Usuario</th><th>Nombre</th><th>Apellido</th><th>Rol</th><th>Email</th><th>Fecha de Creación</th></tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['USUARIOID'] . "</td>";
        echo "<td>" . $row['NOMBREUSUARIO'] . "</td>";
        echo "<td>" . $row['NOMBRE'] . "</td>";
        echo "<td>" . $row['APELLIDO'] . "</td>";
        echo "<td>" . $row['ROL'] . "</td>";
        echo "<td>" . $row['EMAIL'] . "</td>";
        echo "<td>" . $row['FECHACREACION'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

if ($action == "insert") {
    $nombreUsuario = $_POST['nombreUsuario'];
    $contrasena = $_POST['contrasena']; // Asegúrate de hashear esta contraseña antes de insertarla
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $rol = $_POST['rol'];
    $email = $_POST['email'];

    // Hashear la contraseña antes de insertarla en la base de datos
    $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

    $query = "INSERT INTO Usuarios (NombreUsuario, Contraseña, Nombre, Apellido, Rol, Email) VALUES (:nombreUsuario, :contrasena, :nombre, :apellido, :rol, :email)";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':nombreUsuario', $nombreUsuario);
    oci_bind_by_name($stid, ':contrasena', $contrasenaHash);
    oci_bind_by_name($stid, ':nombre', $nombre);
    oci_bind_by_name($stid, ':apellido', $apellido);
    oci_bind_by_name($stid, ':rol', $rol);
    oci_bind_by_name($stid, ':email', $email);

    if (oci_execute($stid)) {
        echo "Usuario agregado con éxito!";
    } else {
        echo "Error al agregar el usuario.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "update") {
    $usuarioID = $_POST['usuarioID'];
    $nombreUsuario = $_POST['nombreUsuario'];
    // No actualizamos la contraseña aquí, pero podrías agregarlo
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $rol = $_POST['rol'];
    $email = $_POST['email'];

    $query = "UPDATE Usuarios SET NombreUsuario = :nombreUsuario, Nombre = :nombre, Apellido = :apellido, Rol = :rol, Email = :email WHERE UsuarioID = :usuarioID";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':usuarioID', $usuarioID);
    oci_bind_by_name($stid, ':nombreUsuario', $nombreUsuario);
    oci_bind_by_name($stid, ':nombre', $nombre);
    oci_bind_by_name($stid, ':apellido', $apellido);
    oci_bind_by_name($stid, ':rol', $rol);
    oci_bind_by_name($stid, ':email', $email);

    if (oci_execute($stid)) {
        echo "Usuario actualizado con éxito!";
    } else {
        echo "Error al actualizar el usuario.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "delete") {
    $usuarioID = $_POST['usuarioID'];

    $query = "DELETE FROM Usuarios WHERE UsuarioID = :usuarioID";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':usuarioID', $usuarioID);

    if (oci_execute($stid)) {
        echo "Usuario eliminado con éxito!";
    } else {
        echo "Error al eliminar el usuario.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
}

oci_close($conn);

// Mostrar la tabla de usuarios después de cualquier acción
mostrarUsuarios($conn);
?>
