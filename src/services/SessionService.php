<?php

namespace services;

class SessionService
{

    private static ?SessionService $instance = null;

    // Tiempo antes de expirar la sesión
    private int $expireAfterSeconds = 3600;

    // Constructor privado: inicia sesion y controla inactividad
    private function __construct()
    {
        // Iniciar sesion si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Comprobar inactividad
        if (
            isset($_SESSION['LAST_ACTIVITY']) &&
            (time() - $_SESSION['LAST_ACTIVITY']) > $this->expireAfterSeconds
        ) {
            $this->logout();
        }

        // Guardar ultima actividad
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    // Devuelve la instancia 
    public static function getInstance(): SessionService
    {
        if (!self::$instance) {
            self::$instance = new SessionService();
        }
        return self::$instance;
    }

    // Obtener variable de sesion
    public function __get(string $name)
    {
        return $_SESSION[$name] ?? null;
    }

    // Guardar variable de sesion
    public function __set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    // Comprobar si el usuario tiene un rol
    public function hasRole(string $role): bool
    {
        if (!isset($_SESSION['roles']) || !is_array($_SESSION['roles'])) {
            return false;
        }
        return in_array($role, $_SESSION['roles']);
    }

    // Cerrar sesion completamente
    public function logout(): void
    {
        // Vaciar variables de sesion
        $_SESSION = [];

        // Borrar cookie de sesion
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destruir sesion
        session_destroy();
    }
}