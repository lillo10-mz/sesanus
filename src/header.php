<?php
require_once __DIR__ . '/../vendor/autoload.php';

use services\SessionService;

// Iniciar sesión mediante SessionService (patrón Singleton)
$session = SessionService::getInstance();

// Verificar si el usuario está logueado
$loggedIn = $session->__get('loggedIn');
$nombreUsuario = $session->__get('nombre');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sesanus</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="icon" href="uploads/favicon.png" type="image/png">
</head>
<body>
<header>
    <div class="izquierda">
        <img src="uploads/sesanus1.jpg" alt="Sesanus" class="logo">
        <nav class="menu">
            <a href="index.php">Inicio</a>
            <a href="productos.php">Productos</a>
            <a href="contacto.php">Contacto</a>
        </nav>
    </div>

    <div class="derecha">
        <?php if ($loggedIn): ?>
            Hola, <?= htmlspecialchars($nombreUsuario ?? 'Usuario') ?> |
            <a href="logout.php">Cerrar sesión</a>
        <?php else: ?>
            <a href="login.php">Iniciar sesión</a>
        <?php endif; ?>
    </div>
</header>
<main>