<?php
require_once __DIR__ . '/../vendor/autoload.php';

use services\SessionService;

// Iniciar sesión
$session = SessionService::getInstance();

require_once 'header.php';
?>

<!-- ==========================
     PORTADA PRINCIPAL
=========================== -->
<div class="portada">
  <div class="contenido">
    <h1>Bienvenido a Sesanus</h1>
    <p>Tu tienda de salud, deporte y bienestar natural.  
       Descubre nuestros productos de calidad seleccionados para tu día a día.</p>
    <a href="productos.php" class="boton">Ver productos</a>
  </div>
</div>

<?php
require_once 'footer.php';
?>