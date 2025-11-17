<?php
require_once __DIR__ . '/../vendor/autoload.php';

use config\Config;
use services\ProductosService;

// =============================
// 1️⃣ Validar parámetro ID
// =============================
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header('Location: productos.php');
    exit;
}

// =============================
// 2️⃣ Conexión y carga del producto
// =============================
$config = Config::getInstance();
$db = $config->__get('db');
$productosService = new ProductosService($db);

$producto = $productosService->findById((int)$id);

if (!$producto) {
    header('Location: productos.php');
    exit;
}

require_once 'header.php';
?>

<!-- =============================
      3️⃣ Detalle de producto
============================= -->
<div class="detalle-producto-container">
    <h1>Detalles del producto</h1>

    <div class="detalle-producto">
        <div class="detalle-imagen">
            <img src="uploads/<?= htmlspecialchars($producto->__get('imagen')) ?>" alt="Imagen del producto">
        </div>

        <div class="detalle-info">
            <dl>
                <dt>ID:</dt>
                <dd><?= htmlspecialchars($producto->__get('id')) ?></dd>

                <dt>Marca:</dt>
                <dd><?= htmlspecialchars($producto->__get('marca')) ?></dd>

                <dt>Modelo:</dt>
                <dd><?= htmlspecialchars($producto->__get('modelo')) ?></dd>

                <dt>Descripción:</dt>
                <dd><?= htmlspecialchars($producto->__get('descripcion')) ?></dd>

                <dt>Precio:</dt>
                <dd><?= htmlspecialchars($producto->__get('precio')) ?> €</dd>

                <dt>Stock disponible:</dt>
                <dd><?= htmlspecialchars($producto->__get('stock')) ?></dd>

                <dt>Categoría:</dt>
                <dd><?= htmlspecialchars($producto->__get('categoriaNombre')) ?></dd>
            </dl>

            <a href="productos.php" class="boton volver">⬅ Volver al listado</a>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>