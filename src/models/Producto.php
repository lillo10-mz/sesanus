<?php

namespace models;

/**
 * Clase modelo Producto
 * Representa cada producto del catálogo de la aplicación.
 */
class Producto
{
    // Atributos principales del producto
    private $id;
    private $uuid;
    private $descripcion;
    private $imagen;
    private $marca;
    private $modelo;
    private $precio;
    private $stock;
    private $createdAt;
    private $updatedAt;
    private $categoriaId;
    private $categoriaNombre;
    private $isDeleted;

    /**
     * Constructor flexible.
     * Permite crear un objeto Producto con los valores principales,
     * ya sea desde la base de datos o de forma manual.
     */
    public function __construct(
        $id = null,
        $uuid = null,
        $descripcion = null,
        $imagen = null,
        $marca = null,
        $modelo = null,
        $precio = null,
        $stock = null,
        $createdAt = null,
        $updatedAt = null,
        $categoriaId = null,
        $categoriaNombre = null,
        $isDeleted = false
    ) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->descripcion = $descripcion;
        $this->imagen = $imagen ?: 'https://via.placeholder.com/150'; // imagen por defecto
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->categoriaId = $categoriaId;
        $this->categoriaNombre = $categoriaNombre;
        $this->isDeleted = $isDeleted;
    }

    /**
     * Métodos mágicos __get y __set
     * Permiten acceder y modificar propiedades de forma dinámica.
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