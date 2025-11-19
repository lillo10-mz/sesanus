<?php

namespace services;

use models\User;
use PDO;
use Exception;

require_once __DIR__ . '/../models/User.php';

class UsersService
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Autenticar usuario por email y contraseña
    public function authenticate(string $email, string $password): User
    {
        // Buscar usuario por email
        $user = $this->findUserByEmail($email);

        if (!$user) {
            throw new Exception('Usuario no encontrado.');
        }

        // Verificar contraseña
        if (!password_verify($password, $user->__get('password'))) {
            throw new Exception('Contraseña incorrecta.');
        }

        return $user;
    }

    // Buscar usuario por email y cargar sus roles
    public function findUserByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email AND is_deleted = FALSE");
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        // Obtener roles del usuario
        $rolesStmt = $this->db->prepare("SELECT roles FROM user_roles WHERE user_id = :id");
        $rolesStmt->execute(['id' => $row['id']]);
        $roles = $rolesStmt->fetchAll(PDO::FETCH_COLUMN);

        // Crear objeto User
        $user = new User(
            $row['id'],
            $row['username'],
            $row['password'],
            $row['nombre'],
            $row['apellidos'],
            $row['email'],
            $row['created_at'],
            $row['updated_at'],
            $row['is_deleted'],
            $roles
        );

        return $user;
    }
}