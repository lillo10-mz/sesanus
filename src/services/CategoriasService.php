<?php

namespace services;

use models\Categoria;
use PDO;
use Exception;

require_once __DIR__ . '/../models/Categoria.php';

/**
 * Clase CategoriasService
 * Gestiona el acceso y la manipulación de categorías en la base de datos.
 */
class CategoriasService
{
    private PDO $db;

    /**
     * Constructor: recibe y almacena la conexión PDO.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Recupera todas las categorías ordenadas por su ID.
     * Devuelve un array de objetos Categoria.
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM categorias ORDER BY id ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $categorias = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categorias[] = new Categoria(
                    $row['id'],
                    $row['nombre'],
                    $row['created_at'],
                    $row['updated_at'],
                    $row['is_deleted']
                );
            }

            return $categorias;
        } catch (Exception $e) {
            throw new Exception("Error al obtener categorías: " . $e->getMessage());
        }
    }

    /**
     * Busca una categoría por su nombre exacto.
     * Devuelve false si no la encuentra, o una instancia de Categoria si existe.
     */
    public function findByName(string $name)
    {
        try {
            $sql = "SELECT * FROM categorias WHERE nombre = :nombre";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nombre', $name, PDO::PARAM_STR);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }

            return new Categoria(
                $row['id'],
                $row['nombre'],
                $row['created_at'],
                $row['updated_at'],
                $row['is_deleted']
            );
        } catch (Exception $e) {
            throw new Exception("Error al buscar categoría: " . $e->getMessage());
        }
    }
}