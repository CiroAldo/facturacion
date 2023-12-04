<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="login">
        <h1>INVENTARIO TESJO</h1>

        <form id="loginForm" action="autenticacion.php" method="post" onsubmit="return validateLogin()">
            <label for="username">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="username" placeholder="Usuario" id="username" autocomplete="off" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Contraseña" id="password" autocomplete="off" required>
            <input type="submit" value="Acceder">
            <!-- Botón para redirigir a la página de registro -->
            <input type="submit" value="Crear Nuevo Usuario" onclick="redirectToRegistro()">
            <!-- Botón para redirigir a la página de recuperación -->
            <input type="submit" value="¿olvide mi contraseña?" onclick="redirectToRecuperacion()">
            <p id="errorMessage" style="color: red; display: none;"></p>
        </form>
    </div>

    <script>
        function redirectToRegistro() {
            window.location.href = 'registro.php';
        }

        function redirectToRecuperacion() {
            window.location.href = 'perder.php';
        }

        function validateLogin() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;

            // Verificar si el campo de usuario está vacío o la contraseña está vacía
            if (username.trim() === "" || password.trim() === "") {
                document.getElementById("errorMessage").innerText = "Por favor, ingrese usuario y contraseña.";
                document.getElementById("errorMessage").style.display = "block";
                return false;
            } else {
                document.getElementById("errorMessage").style.display = "none";
                return true;
            }
        }
    </script>
</body>

</html>


