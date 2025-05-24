<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard RRHH - Sistema de Nómina</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../Imagen/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            height: 100vh;
            color: #003366;
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

        .dashboard-container {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.95);
            margin: 50px auto;
            padding: 2rem 3rem;
            border-radius: 15px;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.25);
            border: 2px solid #003366;
        }

        h1 {
            color: #003366;
            margin-bottom: 1rem;
        }

        p {
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
        }

        nav ul li {
            margin: 10px 0;
        }

        nav ul li a {
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            background-color: #003366;
            color: white;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        nav ul li a:hover {
            background-color: #0055a5;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="dashboard-container">
        <h1>Bienvenido al Sistema de Nómina GT</h1>
        <p>Usuario conectado: <strong><?php echo $_SESSION['usuario']; ?></strong></p>

        <nav>
            <ul>
                <li><a href="empleados_rrhh.php">Gestión de Empleados</a></li>
                <li><a href="Reportes.php">Reportes</a></li>
                <li><a href="nomina.php">Nomina</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>
