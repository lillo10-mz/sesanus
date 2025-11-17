<?php

require_once __DIR__ . '/../vendor/autoload.php';

use config\Config;
use services\SessionService;
use services\CategoriasService;
use services\ProductosService;
use models\Producto;


// CONTROL DE ACCESO

$session = SessionService::getInstance();
$loggedIn = $session->__get('loggedIn');
$roles = $session->__get('roles') ?? [];
$isAdmin = in_array('ADMIN', $roles, true);

if (!$loggedIn || !$isAdmin) {
    header('Location: productos.php');
    exit;
}


//CARGA DE SERVICIOS Y DEPENDENCIAS

$config = Config::getInstance();
$db = $config->__get('db');

$categoriasService = new CategoriasService($db);
$productosService = new ProductosService($db);
$categorias = $categoriasService->findAll();

$errores = [];
$exito = null;


//  PROCESO DEL FORMULARIO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Filtrar entradas
    $marca = trim($_POST['marca'] ?? '');
    $modelo = trim($_POST['modelo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = trim($_POST['precio'] ?? '');
    $stock = trim($_POST['stock'] ?? '');
    $categoriaId = trim($_POST['categoria_id'] ?? '');

    // Validaciones
    if ($marca === '') $errores[] = 'La marca es obligatoria.';
    if ($modelo === '') $errores[] = 'El modelo es obligatorio.';
    if ($descripcion === '') $errores[] = 'La descripción es obligatoria.';
    if ($precio === '' || !is_numeric($precio)) $errores[] = 'El precio debe ser numérico.';
    if ($stock === '' || filter_var($stock, FILTER_VALIDATE_INT) === false) $errores[] = 'El stock debe ser un número entero.';
    if ($categoriaId === '') $errores[] = 'Debes seleccionar una categoría.';

    // Subida de imagen
    $nombreImagenFinal = null;
    if (!empty($_FILES['imagen']['name'])) {
        if ($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $mime = mime_content_type($_FILES['imagen']['tmp_name']);
            $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($mime, $permitidos, true)) {
                $errores[] = 'La imagen debe ser JPG, PNG o WEBP.';
            } else {
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
                    $errores[] = 'No se pudo guardar la imagen.';
                }
            }
        } else {
            $errores[] = 'Error al subir la imagen.';
        }
    }

    // Si no hay errores, guardar producto
    if (empty($errores)) {
        $producto = new Producto(
            id: null,
            uuid: null,
            descripcion: $descripcion,
            imagen: $nombreImagenFinal,
            marca: $marca,
            modelo: $modelo,
            precio: (float)$precio,
            stock: (int)$stock,
            createdAt: null,
            updatedAt: null,
            categoriaId: $categoriaId,
            categoriaNombre: null,
            isDeleted: false
        );

        try {
            $ok = $productosService->save($producto);
            if ($ok) {
                header('Location: productos.php');
                exit;
            } else {
                $errores[] = 'Error al insertar el producto en la base de datos.';
            }
        } catch (Throwable $t) {
            $errores[] = 'Excepción: ' . $t->getMessage();
        }
    }
}


//  HTML

require_once 'header.php';
?>

<h1 class="titulo" style="margin-top:10px;">Crear producto</h1>
<p class="descripcion">Rellena los datos del nuevo producto. Todos los campos son obligatorios salvo la imagen.</p>

<div class="contenedor-productos" style="max-width:740px;">
    <?php if (!empty($errores)): ?>
        <div style="background:#ffe8e8;border:1px solid #e0a0a0;color:#a33;padding:10px;border-radius:6px;margin-bottom:15px;">
            <strong>Se han encontrado errores:</strong>
            <ul style="margin-left:18px;">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="formulario" action="create.php" method="post" enctype="multipart/form-data">
        <label for="imagen">Imagen (opcional):</label>
        <input type="file" name="imagen" id="imagen" accept="image/jpeg,image/png,image/webp">

        <label for="marca">Marca:</label>
        <input type="text" name="marca" id="marca" required>

        <label for="modelo">Modelo:</label>
        <input type="text" name="modelo" id="modelo" required>

        <label for="descripcion">Descripción:</label>
        <input type="text" name="descripcion" id="descripcion" required>

        <label for="precio">Precio (€):</label>
        <input type="number" name="precio" id="precio" step="0.01" required>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" min="0" step="1" required>

        <label for="categoria_id">Categoría:</label>
        <select name="categoria_id" id="categoria_id" required>
            <option value="">-- Selecciona una categoría --</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= htmlspecialchars($cat->__get('id')) ?>">
                    <?= htmlspecialchars($cat->__get('nombre')) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="botones">
            <button type="submit">Crear producto</button>
            <a href="productos.php" class="boton" style="background:#aaa;margin-left:10px;">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once 'footer.php'; ?>