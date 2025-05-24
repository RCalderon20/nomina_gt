<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
session_destroy(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Nómina GT</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background: url('../Imagen/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 200, 0.3);
            z-index: 0;
        }

        .login-container {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.97);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
            text-align: center;
            border: 2px solid #003366;
        }

        .login-container h2 {
            color: #003366;
            margin-bottom: 1.5rem;
        }

        form label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #333;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        form input[type="text"]:focus,
        form input[type="password"]:focus {
            border-color: #003366;
            outline: none;
        }

        form input[type="submit"] {
            background-color: #003366;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #002244;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="login-container">
        <h2>Login - Nómina GT</h2>
        <?php if ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="../index.php?accion=login" method="post">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" required>

            <input type="submit" value="Ingresar">
        </form>
    </div>
</body>
</html>
