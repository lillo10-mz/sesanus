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


// VALIDACIÓN DEL FORMULARIO

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['imagen']['name']) || empty($_POST['id'])) {
    header('Location: productos.php');
    exit;
}


// CARGA DE SERVICIOS Y PRODUCTO

$config = Config::getInstance();
$db = $config->__get('db');
$productosService = new ProductosService($db);

$id = (int)$_POST['id'];
$producto = $productosService->findById($id);
if (!$producto) {
    header('Location: productos.php');
    exit;
}


// VALIDACIÓN DE IMAGEN

$mime = mime_content_type($_FILES['imagen']['tmp_name']);
$permitidos = ['image/jpeg', 'image/png', 'image/webp'];

if (!in_array($mime, $permitidos, true)) {
    header('Location: productos.php');
    exit;
}


// PROCESAMIENTO DE IMAGEN

$ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
$base = pathinfo($_FILES['imagen']['name'], PATHINFO_FILENAME);
$base = preg_replace('/[^a-zA-Z0-9_-]/', '-', strtolower($base));
$unico = bin2hex(random_bytes(4));
$nombreImagenFinal = $base . '-' . $unico . '.' . strtolower($ext);

$destino = __DIR__ . '/uploads/' . $nombreImagenFinal;

if (!is_dir(__DIR__ . '/uploads')) {
    mkdir(__DIR__ . '/uploads', 0775, true);
}

if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
    header('Location: productos.php');
    exit;
}


// ACTUALIZACIÓN EN BASE DE DATOS

$producto->__set('imagen', $nombreImagenFinal);
$productosService->update($producto);


// REDIRECCIÓN FINAL

header('Location: update-image.php?id=' . $id);
exit;