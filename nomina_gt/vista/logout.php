<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cerrando sesión...</title>
    <meta http-equiv="refresh" content="2;url=../index.php"> <!-- Redirige a index en 2 segundos -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;  
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .mensaje {
            font-size: 1.5em;
            color: #333;
        }
        .loader {
            margin-top: 20px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="mensaje">Cerrando sesión...</div>
<div class="loader"></div>

</body>
</html>
