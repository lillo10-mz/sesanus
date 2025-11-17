<?php

namespace models;

/**
 * Clase modelo Categoria
 * Representa una categoría de producto dentro del sistema.
 */
class Categoria
{
    private $id;
    private $nombre;
    private $createdAt;
    private $updatedAt;
    private $isDeleted;

    /**
     * Constructor flexible: permite inicializar todos los campos.
     */
    public function __construct(
        $id = null,
        $nombre = null,
        $createdAt = null,
        $updatedAt = null,
        $isDeleted = false
    ) {
        $this->id = $id; // El UUID lo genera PostgreSQL
        $this->nombre = $nombre;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->isDeleted = $isDeleted;
    }

    /**
     * Devuelve el ID de la categoría.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Métodos mágicos para acceder y modificar propiedades dinámicamente.
     */
    public function __get($name)
    {
        return $this->$name ?? null;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}