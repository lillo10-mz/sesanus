<?php
/**
 * Página de listado de productos.
 * - Recupera todos los productos con el nombre de su categoría (JOIN).
 * - Muestra botones de acción condicionados por rol:
 *   - ADMIN: puede crear, editar y eliminar.
 *   - USER (o no autenticado): solo puede ver detalles.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use config\Config;
use services\ProductosService;
use services\SessionService;

// 1) Obtener conexión y servicio de sesión
$config = Config::getInstance();
$db = $config->__get('db');
$session = SessionService::getInstance();

// 2) Servicio de productos
$productosService = new ProductosService($db);

// 3) Cargar productos (con nombre de categoría incluido)
$search = trim($_GET['search'] ?? '');
$productos = $productosService->findAllWithCategoryName($search);

// 4) Calcular permisos según roles en sesión
//    - hasRole('ADMIN') -> true si el usuario logueado es administrador
//    - hasRole('USER')  -> true si el usuario tiene rol básico de usuario
$isAdmin = $session->hasRole('ADMIN');
$isUser  = $session->hasRole('USER');
?>

<?php require_once 'header.php'; ?>

<h1 class="titulo">Nuestros productos</h1>
<p class="descripcion">
  Aquí encontrarás todos los productos de Sesanus.
  Selecciona uno para ver más detalles o gestiona el inventario si eres administrador.
</p>

<div class="contenedor-productos">

  <?php
  /**
   * Botón "Añadir nuevo producto"
   * - Visible solo para ADMIN.
   */
  ?>
  <form method="get" action="productos.php" class="buscador-productos">
      <input type="text" name="search" placeholder="Buscar por marca o modelo..." 
           value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="boton">🔍 Buscar</button>
    </form>
  <?php if ($isAdmin): ?>
    <div class="boton-crear">
      <a href="create.php" class="boton">➕ Añadir nuevo producto</a>
    </div>
  <?php endif; ?>

  <!-- Tabla de productos -->
  <table class="tabla-productos">
    <thead>
      <tr>
        <th>ID</th>
        <th>Imagen</th>
        <th>Marca</th>
        <th>Modelo</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Categoría</th>
        <th>Acciones</th>
      </tr>
    </thead>

    <tbody>
      <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $producto): ?>
          <tr>
            <td><?= htmlspecialchars($producto->__get('id')) ?></td>

            <td>
              <img
                src="uploads/<?= htmlspecialchars($producto->__get('imagen')) ?>"
                alt="imagen producto"
                class="img-tabla"
              >
            </td>

            <td><?= htmlspecialchars($producto->__get('marca')) ?></td>
            <td><?= htmlspecialchars($producto->__get('modelo')) ?></td>
            <td><?= htmlspecialchars($producto->__get('descripcion')) ?></td>
            <td><?= htmlspecialchars($producto->__get('precio')) ?> €</td>
            <td><?= htmlspecialchars($producto->__get('stock')) ?></td>
            <td><?= htmlspecialchars($producto->__get('categoriaNombre')) ?></td>

            <td class="acciones">
              <?php
              ?>
              <a
                href="details.php?id=<?= $producto->__get('id') ?>"
                class="boton boton-detalles"
                title="Ver detalles"
              >🔍</a>

              <?php
              ?>
              <?php if ($isAdmin): ?>
                <a
                  href="update.php?id=<?= $producto->__get('id') ?>"
                  class="boton boton-editar"
                  title="Editar producto"
                >✏️</a>

                <a href="delete.php?id=<?= $producto->__get('id') ?>"
                  class="boton boton-borrar"
                  onclick="return confirm('¿Seguro que quieres eliminar este producto?');">🗑️
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" style="text-align:center;">No hay productos disponibles.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'footer.php'; ?>