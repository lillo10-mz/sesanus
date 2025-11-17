📌 Sesanus · Gestión de tienda

Aplicación web con CRUD de productos, autenticación por roles y subida de imágenes.
Proyecto desarrollado como práctica de PHP, PostgreSQL, Docker y despliegue con Docker/Apache.

1. 🧩 Características principales

   -Listado de productos con imagen en miniatura, precio, stock y categoría.
   -Detalle de producto con información completa.
   -Crear productos (admin).
   -Editar productos (admin).
   -Eliminar productos (admin).
   -Actualizar imagen del producto desde archivo (admin).
   -Autenticación con roles USER y ADMIN.
   -Control de permisos en toda la aplicación.
   -Categorías dinámicas cargadas desde la base de datos.
   -Interfaz adaptada y estructurada.


2. 🛠️ Tecnologías utilizadas

   -PHP 8+
   -PostgreSQL 12.0+
   -Docker 
   -HTML5 + CSS3
   -Composer 2.0


3. 📁 Estructura del proyecto
    / vendor
    / src
        ├── config/Config.php
        ├── models/ (Producto.php, Categoria.php, User.php)
        ├── services/ (ProductosService.php, CategoriasService.php, UsersService.php, SessionService.php)
        ├── uploads/ (imagenes)
        ├── *.php (index, contacto, login, logout, productos, create, update, update-image, details, delete…)
        ├── estilos.css
    / database
        └── init.sql
    env
    composer.json
    composer.lock
    docker-compose.yml
    Dockerfile
    readme.txt



4. 🛠️ Instrucciones de la instalacion - Requisitos previos
    - PHP 8.0+ 
    - Composer 2.x
    - PostgreSQL 12+
    - Docker 20+/Compose v2+
    - Git (para clonar el repositorio)



 

5. 📦 Instalación con Docker
    1. Clona el repositorio
       git clone <url-del-repo>
       cd Sesanus

    2. Arranca los contenedores
       docker compose up -d --build

   3. La base de datos se crea automáticamente
      (init.sql se ejecuta al arrancar por primera vez el contenedor postgres)

   4. Accede a la aplicación
      http://localhost:8080/

   5. Acceso a Adminer (si está activado)
      http://localhost:8081/




6. 🔐 Usuarios de prueba
      Rol	  Usuario	           Contraseña
     Admin	  admin@sesanus.com    admin
     Usuario  user@sesanus.com     admin
(Ambos tienen contraseña "admin" porque así lo decidimos durante el desarrollo.)




7. 🧭 Navegación principal

   - Inicio → Portada con imagen y acceso a productos.
   - Productos
     - Listado completo con acciones.
     - Barra de busqueda por modelo, categoria, descripcion y marca. 
     - Detalle completo con imagen ampliada.
     - Crear productos (admin).
     - Editar productos (admin).
     - Eliminar productos (admin).
     - Cambiar imagen del producto (admin).
   - Contacto → Formulario.
   - Login / Logout → Sistema de acceso por roles.




8. 📌 Funcionamiento del CRUD

   - ✔ Crear productos
       Formulario con (Marca, Modelo, Descripción, Precio, Stock, Categoría, Imagen (opcional, subida al directorio /uploads))

   - ✔ Editar productos
       Permite modificar todos los campos excepto el UUID.

   - ✔ Actualizar imagen
       Pantalla específica para sustituir la imagen del producto.

   - ✔ Borrar productos
       Confirmación por JavaScript → eliminación permanente en base de datos.

   - ✔ Detalles productos
       Permite ver los detalles de los porductos.
adios
9. 👨‍💻 Autor
    Miguel Zamora
    Repositorio original: https://github.com/lillo10-mz/sesanus.git

10. 📄 Licencia
    Este proyecto está licenciado bajo Creative Commons BY-NC 4.0.
    Esto significa que:
     - Se permite usar y compartir el proyecto
     - Se debe mencionar la autoría
     - No está permitido el uso comercial
    Más información:
    https://creativecommons.org/licenses/by-nc/4.0/


