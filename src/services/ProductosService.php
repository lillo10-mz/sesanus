<?php

namespace services;

use models\Producto;
use PDO;
use Exception;

require_once __DIR__ . '/../models/Producto.php';

class ProductosService
{
    private PDO $db; // conexion a la base de datos

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    // Método que obtiene todos los productos junto con el nombre de su categoría. Tambien barra de busqueda
    public function findAllWithCategoryName(string $searchTerm = ''): array
    {
        try {
            // Consulta: obtiene productos + nombre de categoría (o "Sin categoría")
            $sql = "SELECT 
                        p.*,
                        COALESCE(c.nombre, 'Sin categoría') AS categoria_nombre
                    FROM productos p
                    LEFT JOIN categorias c ON p.categoria_id = c.id";

            // barra de busqueda en modelo, categoria y descripcion
            if (!empty($searchTerm)) {
            $sql .= " WHERE p.marca ILIKE :term 
                    OR p.modelo ILIKE :term
                    OR c.nombre ILIKE :term 
                    OR p.descripcion ILIKE :term ";
            }

            // Ordena por ID ascendente
            $sql .= " ORDER BY p.id ASC";

            $stmt = $this->db->prepare($sql); // prepara la consulta

            // Si hay un término de búsqueda, nos lo da.
            if (!empty($searchTerm)) {
                $term = '%' . $searchTerm . '%';
                $stmt->bindParam(':term', $term, PDO::PARAM_STR);
            }

            $stmt->execute(); // ejecuta la consulta

            $productos = [];
            
            // Recorre todos los resultados y crea objetos Producto
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $productos[] = new Producto(
                    $row['id'],
                    $row['uuid'],
                    $row['descripcion'],
                    $row['imagen'],
                    $row['marca'],
                    $row['modelo'],
                    $row['precio'],
                    $row['stock'],
                    $row['created_at'],
                    $row['updated_at'],
                    $row['categoria_id'],
                    $row['categoria_nombre'],   
                    $row['is_deleted']
                );
            }

            return $productos; // devuelve array de Productos
        } catch (Exception $e) {
            
            throw new Exception("Error al obtener productos: " . $e->getMessage());
        }
    }

    public function findById(int $id): ?Producto
    {
        try {
            // Busca un solo producto por id + nombre de categoría
            $sql = "SELECT 
                        p.*,
                        COALESCE(c.nombre, 'Sin categoría') AS categoria_nombre
                    FROM productos p
                    LEFT JOIN categorias c ON p.categoria_id = c.id
                    WHERE p.id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // vinculamos el id
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return null; // si no existe, devolve null
            }

            // devolvemos el objeto Producto completo
            return new Producto(
                $row['id'],
                $row['uuid'],
                $row['descripcion'],
                $row['imagen'],
                $row['marca'],
                $row['modelo'],
                $row['precio'],
                $row['stock'],
                $row['created_at'],
                $row['updated_at'],
                $row['categoria_id'],
                $row['categoria_nombre'],  
                $row['is_deleted']
            );
        } catch (Exception $e) {
            throw new Exception("Error al buscar producto: " . $e->getMessage());
        }
    }

    public function save(Producto $producto): bool
    {
        try {
            // Inserta un producto nuevo. uuid se genera automáticamente
            $sql = "INSERT INTO productos 
                        (uuid, descripcion, imagen, marca, modelo, precio, stock, categoria_id, created_at, updated_at, is_deleted)
                    VALUES 
                        (gen_random_uuid(), :descripcion, :imagen, :marca, :modelo, :precio, :stock, :categoria_id, NOW(), NOW(), FALSE)";

            $stmt = $this->db->prepare($sql);

            // Si no hay imagen, pone una por defecto
            $imagen = $producto->__get('imagen') ?: 'https://via.placeholder.com/150';

            // vinculamos parámetros uno por uno
            $stmt->bindValue(':descripcion', $producto->__get('descripcion'));
            $stmt->bindValue(':imagen', $imagen);
            $stmt->bindValue(':marca', $producto->__get('marca'));
            $stmt->bindValue(':modelo', $producto->__get('modelo'));
            $stmt->bindValue(':precio', $producto->__get('precio'));
            $stmt->bindValue(':stock', $producto->__get('stock'), PDO::PARAM_INT);
            $stmt->bindValue(':categoria_id', $producto->__get('categoriaId'));

            return $stmt->execute(); // devolvemos true o false
        } catch (Exception $e) {
            throw new Exception("Error al guardar producto: " . $e->getMessage());
        }
    }

    public function update(Producto $producto): bool
    {
        try {
            // Actualiza un producto existente
            $sql = "UPDATE productos
                    SET descripcion = :descripcion,
                        imagen = :imagen,
                        marca = :marca,
                        modelo = :modelo,
                        precio = :precio,
                        stock = :stock,
                        categoria_id = :categoria_id,
                        updated_at = NOW()
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            // Parámetros vinculados igual que en save()
            $stmt->bindValue(':id', $producto->__get('id'), PDO::PARAM_INT);
            $stmt->bindValue(':descripcion', $producto->__get('descripcion'));
            $stmt->bindValue(':imagen', $producto->__get('imagen'));
            $stmt->bindValue(':marca', $producto->__get('marca'));
            $stmt->bindValue(':modelo', $producto->__get('modelo'));
            $stmt->bindValue(':precio', $producto->__get('precio'));
            $stmt->bindValue(':stock', $producto->__get('stock'), PDO::PARAM_INT);
            $stmt->bindValue(':categoria_id', $producto->__get('categoriaId'));

            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error al actualizar producto: " . $e->getMessage());
        }
    }

    public function deleteById(int $id): bool
    {
        try {
            // Elimina un producto por su ID
            $sql = "DELETE FROM productos WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error al eliminar producto: " . $e->getMessage());
        }
    }
}