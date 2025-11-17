<?php
require_once __DIR__ . '/../vendor/autoload.php';

use config\Config;
use services\SessionService;
use services\UsersService;

// Iniciar servicios
$config = Config::getInstance();
$session = SessionService::getInstance();
$userService = new UsersService($config->__get('db'));

// Si ya hay sesión activa, redirigir al index
if ($session->__get('loggedIn')) {
    header('Location: index.php');
    exit;
}

$error = null;

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    try {
        if (empty($email) || empty($password)) {
            throw new Exception('Debes introducir el correo y la contraseña.');
        }

        // Autenticación con UsersService
        $user = $userService->authenticate($email, $password);

        // Guardar datos en sesión
        $session->__set('loggedIn', true);
        $session->__set('userId', $user->__get('id'));
        $session->__set('username', $user->__get('username'));
        $session->__set('nombre', $user->__get('nombre'));
        $session->__set('roles', $user->__get('roles'));

        // Redirigir al inicio tras login correcto
        header('Location: index.php');
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<?php require_once 'header.php'; ?>

<div class="h1register">
  <h1>Iniciar sesión</h1>
</div>
<div class="h1register">
  <p>Introduce tus datos para acceder a tu cuenta de Sesanus.</p>
</div>

<?php if ($error): ?>
  <div class="error" style="color:red; text-align:center; margin-bottom:10px;">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<form action="login.php" method="post" class="formulario">
    <label for="email">Correo electrónico:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" required>
    
    <div class="botones">
      <button type="submit">Iniciar sesión</button>
    </div>
</form>

<?php require_once 'footer.php'; ?>