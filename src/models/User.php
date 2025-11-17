<?php

namespace models;

/**
 * Clase modelo User
 * - Representa al usuario del sistema con sus datos y roles.
 * - Se utiliza para transferir/gestionar datos entre servicios y vistas.
 */
class User
{
    private $id;
    private $username;
    private $password;
    private $nombre;
    private $apellidos;
    private $email;
    private $createdAt;
    private $updatedAt;
    private $isDeleted;

    /** Array de roles del usuario (p.ej. ['ADMIN'] o ['USER']) */
    private $roles = [];

    /**
     * Constructor: inicializa todos los atributos del usuario.
     */
    public function __construct(
        $id = null,
        $username = null,
        $password = null,
        $nombre = null,
        $apellidos = null,
        $email = null,
        $createdAt = null,
        $updatedAt = null,
        $isDeleted = false,
        $roles = []
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = $email;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->isDeleted = $isDeleted;
        $this->roles = $roles;
    }

    /**
     * Getter mágico: acceso dinámico a propiedades privadas.
     */
    public function __get($name)
    {
        return $this->$name ?? null;
    }

    /**
     * Setter mágico: modificación dinámica de propiedades privadas.
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}