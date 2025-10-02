# API de Productos en Laravel

[![Laravel](https://img.shields.io/badge/Laravel-12-orange.svg)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8-blue.svg)](https://www.mysql.com/)
[![JWT](https://img.shields.io/badge/JWT-auth-green.svg)](https://jwt.io/)
[![Swagger](https://img.shields.io/badge/Swagger-OpenAPI-yellow.svg)](https://swagger.io/)

## Descripción

Esta API fue diseñada como parte de la materia **Programación 4** para fines educativos.  
Permite gestionar productos con operaciones CRUD (Crear, Leer, Actualizar, Eliminar) usando **Laravel**, con autenticación mediante **JWT** y documentación interactiva con **Swagger/OpenAPI**.

El proyecto es **de uso público y demostrativo**, ideal para practicar y enseñar conceptos de desarrollo backend con Laravel.

---

## Tecnologías utilizadas

-   **Laravel 12**
-   **PHP 8.2+**
-   **MySQL**
-   **JWT Authentication** (`tymon/jwt-auth`)
-   **Swagger/OpenAPI**
-   **Composer** para gestión de dependencias

---

## Funcionalidades

-   **CRUD completo de productos**:
    -   `GET /api/productos` → Listar todos los productos
    -   `GET /api/productos/{id}` → Obtener un producto por ID
    -   `POST /api/productos` → Crear un producto
    -   `PUT /api/productos/{id}` → Actualizar un producto
    -   `DELETE /api/productos/{id}` → Eliminar un producto
-   Validación de datos en todos los endpoints
-   Manejo de errores con códigos HTTP adecuados (`200`, `201`, `404`, `422`, `500`)
-   Documentación interactiva con **Swagger UI**
-   Autenticación vía **JWT** para endpoints protegidos

---

## Instalación

1. **Clonar el repositorio**

    ```bash
    git clone https://github.com/tu-usuario/tu-repo.git
    cd tu-repo
    ```

2. **Instalar dependencias de PHP con Composer**

    ```bash
    composer install
    ```

3. **Crear el archivo .env y configurar la base de datos**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Edita el archivo `.env` con tus credenciales de base de datos.

4. **Ejecutar migraciones**

    ```bash
    php artisan migrate
    ```

5. **(Opcional) Seed de datos de prueba**

    ```bash
    php artisan db:seed
    ```

6. **Iniciar el servidor**

    ```bash
    php artisan serve
    ```

7. **Acceder a Swagger UI (documentación de la API)**
    ```
    http://127.0.0.1:8000/api/documentation
    ```

---

## Autenticación

Se utiliza JWT para proteger los endpoints.

-   Para generar un token, autentica el usuario:

    ```
    POST /api/login
    ```

    **Parámetros:** `email` y `password` del usuario registrado.

-   La respuesta devuelve el `access_token` que se debe enviar en los requests protegidos:
    ```
    Authorization: Bearer <token>
    ```

---

## Autor

**Pablo Pérez** - Técnico Programador UTN Concordia

---

## Licencia

Este proyecto está bajo la licencia MIT.
