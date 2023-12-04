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

function mostrarCategorias($conn) {
    $query = "SELECT * FROM Categorias";
    $stid = oci_parse($conn, $query);
    oci_execute($stid);

    echo "<table border='1'>";
    echo "<tr><th>ID Categoría</th><th>Nombre</th><th>Descripción</th></tr>";
    while ($row = oci_fetch_assoc($stid)) {
        echo "<tr>";
        echo "<td>" . $row['CATEGORIAID'] . "</td>";
        echo "<td>" . $row['NOMBRE'] . "</td>";
        echo "<td>" . $row['DESCRIPCION'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

if ($action == "insert") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $query = "INSERT INTO Categorias (Nombre, Descripcion) VALUES (:nombre, :descripcion)";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':nombre', $nombre);
    oci_bind_by_name($stid, ':descripcion', $descripcion);

    if (oci_execute($stid)) {
        echo "Categoría agregada con éxito!";
    } else {
        echo "Error al agregar la categoría.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "update") {
    $categoriaID = $_POST['categoriaID'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $query = "UPDATE Categorias SET Nombre = :nombre, Descripcion = :descripcion WHERE CategoriaID = :categoriaID";
    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':categoriaID', $categoriaID);
    oci_bind_by_name($stid, ':nombre', $nombre);
    oci_bind_by_name($stid, ':descripcion', $descripcion);

    if (oci_execute($stid)) {
        echo "Categoría actualizada con éxito!";
    } else {
        echo "Error al actualizar la categoría.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
} elseif ($action == "delete") {
    $categoriaID = $_POST['categoriaID'];

    $query = "DELETE FROM Categorias WHERE CategoriaID = :categoriaID";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':categoriaID', $categoriaID);

    if (oci_execute($stid)) {
        echo "Categoría eliminada con éxito!";
    } else {
        echo "Error al eliminar la categoría.";
        $e = oci_error($stid);
        echo htmlentities($e['message']);
    }
}

oci_close($conn);

// Mostrar la tabla de categorías después de cualquier acción
mostrarCategorias($conn);
?>

