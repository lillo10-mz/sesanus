<?php

require_once __DIR__ . '/../vendor/autoload.php';

use config\Config;
use services\SessionService;
use services\ProductosService;

// CONTROL DE ACCESO

$session = SessionService::getInstance();
$loggedIn = $session->__get('loggedIn');
$roles = $session->__get('roles') ?? [];
$isAdmin = in_array('ADMIN', $roles, true);

if (!$loggedIn || !$isAdmin) {
    header('Location: productos.php');
    exit;
}


// CARGA DE SERVICIOS Y VALIDACIÓN DE ID

$config = Config::getInstance();
$db = $config->__get('db');
$productosService = new ProductosService($db);

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: productos.php');
    exit;
}


// ELIMINACIÓN DEL PRODUCTO

try {
    $productosService->deleteById((int)$id);
} catch (Throwable $t) {
    // En caso de error, podrías registrar el error o mostrar un mensaje
    error_log('Error al eliminar producto: ' . $t->getMessage());
}

// REDIRECCIÓN FINAL

header('Location: productos.php');
exit;