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


// CARGA DE SERVICIOS Y DEPENDENCIAS

$config = Config::getInstance();
$db = $config->__get('db');
$productosService = new ProductosService($db);


// VALIDACIÓN DE ID Y CARGA DE PRODUCTO

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: productos.php');
    exit;
}

$producto = $productosService->findById((int)$id);
if (!$producto) {
    header('Location: productos.php');
    exit;
}


// HTML

require_once 'header.php';
?>

<h1 class="titulo" style="margin-top:10px;">Actualizar imagen</h1>
<p class="descripcion">Selecciona una nueva imagen para el producto.</p>

<div class="contenedor-productos" style="max-width:740px;">
    <div style="margin-bottom: 20px;">
        <p><strong>ID:</strong> <?= htmlspecialchars($producto->__get('id')) ?></p>
        <p><strong>Marca:</strong> <?= htmlspecialchars($producto->__get('marca')) ?></p>
        <p><strong>Modelo:</strong> <?= htmlspecialchars($producto->__get('modelo')) ?></p>
        <p><strong>Imagen actual:</strong></p>
        <?php if ($producto->__get('imagen')): ?>
            <img src="uploads/<?= htmlspecialchars($producto->__get('imagen')) ?>" alt="imagen producto" style="width:150px;border:1px solid #ccc;border-radius:4px;">
        <?php else: ?>
            <p>No hay imagen disponible.</p>
        <?php endif; ?>
    </div>

    <form class="formulario" action="update_image_file.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($producto->__get('id')) ?>">

        <label for="imagen">Nueva imagen:</label>
        <input type="file" name="imagen" id="imagen" accept="image/jpeg,image/png,image/webp" required>

        <div class="botones">
            <button type="submit">Actualizar imagen</button>
            <a href="productos.php" class="boton" style="background:#aaa;margin-left:10px;">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once 'footer.php'; ?>