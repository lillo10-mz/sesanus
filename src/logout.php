<?php
require_once __DIR__ . '/../vendor/autoload.php';

use services\SessionService;

// Recuperar instancia única del servicio de sesión
$session = SessionService::getInstance();

// Cerrar sesión
$session->logout();

// Redirigir al inicio
header('Location: index.php');
exit;