<?php

namespace services;

/**
 * Class SessionService
 * Servicio de sesión con patrón Singleton.
 * - Centraliza toda la gestión de sesión (inicio, expiración, variables).
 * - Añade helpers para trabajar con roles (hasRole).
 */
class SessionService
{
    /** Instancia única del Singleton */
    private static ?SessionService $instance = null;

    /** Tiempo de inactividad permitido antes de expirar (1 hora) */
    private int $expireAfterSeconds = 3600;

    /**
     * Constructor privado:
     * - Inicia la sesión si no está iniciada.
     * - Controla la expiración automática según LAST_ACTIVITY.
     */
    private function __construct()
    {
        // 1) Iniciar sesión si procede
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2) Control de expiración automática por inactividad
        if (
            isset($_SESSION['LAST_ACTIVITY']) &&
            (time() - $_SESSION['LAST_ACTIVITY']) > $this->expireAfterSeconds
        ) {
            // Si ha pasado más de $expireAfterSeconds, cerramos sesión
            $this->logout();
        }

        // 3) Actualizar marca de tiempo de la última actividad
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    /**
     * Devuelve la instancia única del servicio (patrón Singleton).
     */
    public static function getInstance(): SessionService
    {
        if (!self::$instance) {
            self::$instance = new SessionService();
        }
        return self::$instance;
    }

    /**
     * Getter mágico de variables de sesión.
     * - Permite acceder así: $session->loggedIn, $session->roles, etc.
     */
    public function __get(string $name)
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * Setter mágico de variables de sesión.
     * - Permite asignar así: $session->loggedIn = true;
     */
    public function __set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Comprueba si el usuario autenticado posee un rol concreto.
     * - Espera que en $_SESSION['roles'] haya un array de strings (p.ej. ['ADMIN','USER']).
     */
    public function hasRole(string $role): bool
    {
        if (!isset($_SESSION['roles']) || !is_array($_SESSION['roles'])) {
            return false;
        }
        return in_array($role, $_SESSION['roles']);
    }

    /**
     * Cierra completamente la sesión actual.
     * - Limpia variables de sesión.
     * - Invalida la cookie de sesión si existe.
     * - Destruye la sesión en el servidor.
     */
    public function logout(): void
    {
        // 1) Vaciar array de sesión
        $_SESSION = [];

        // 2) Invalida la cookie de sesión si el sistema la usa
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

        // 3) Destruir la sesión en el servidor
        session_destroy();
    }
}