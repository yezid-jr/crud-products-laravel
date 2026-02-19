# CRUD de Productos con Laravel 12

Aplicacion web para gestionar productos con sistema de roles. Construida con Laravel 12, MySQL, Tailwind CSS y Jetstream.

**Autor:** Yesid Castro  
**Stack:** Laravel 12 · PHP 8.3 · MySQL 8 · Tailwind CSS · Docker

---

## Requisitos

- Docker Desktop instalado y corriendo
- Git

No necesitas PHP, Composer ni MySQL instalados en tu maquina. Todo corre dentro de Docker.

---

## Instalacion desde cero

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd crud-productos
```

### 2. Configurar el archivo de entorno

Copia el archivo de ejemplo y editalo:

```bash
cp .env.example .env
```

Asegurate de que las variables de base de datos queden asi:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=crud_products
DB_USERNAME=root
DB_PASSWORD=secret
```

> El `DB_HOST=db` apunta al contenedor de MySQL, no a localhost.

### 3. Construir y levantar los contenedores

```bash
docker-compose up -d --build
```

Esto levanta tres contenedores: PHP, Nginx y MySQL. La primera vez puede tardar unos minutos mientras descarga las imagenes.

### 4. Generar la clave de la aplicacion

```bash
docker-compose exec app php artisan key:generate
```

### 5. Ejecutar las migraciones

Espera unos 20 segundos despues del paso anterior para que MySQL termine de inicializarse, luego:

```bash
docker-compose exec app php artisan migrate
```

### 6. Crear el usuario administrador y usuarios de prueba

```bash
docker-compose exec app php artisan db:seed --class=RolesSeeder
```

### 7. Acceder a la aplicacion

Abre el navegador en: [http://localhost:8080](http://localhost:8080)

---

## Usuarios de prueba

| Email | Contrasena | Rol |
|-------|------------|-----|
| admin@example.com | password | Admin |
| test@example.com | password | Usuario |
| john@example.com | password | Usuario |

---

## Sistema de roles

### Administrador

- Ve todos los productos de todos los usuarios
- Ve la columna "Propietario" en el listado
- Puede editar y eliminar cualquier producto
- Puede crear productos propios

### Usuario

- Ve unicamente sus propios productos
- Puede crear, editar y eliminar sus productos
- No puede ver ni modificar productos de otros usuarios

---

## Comandos del dia a dia

### Docker

```bash
# Levantar la aplicacion
docker-compose up -d

# Levantar y reconstruir imagenes (tras cambios en Dockerfile)
docker-compose up -d --build

# Detener la aplicacion
docker-compose down

# Detener y eliminar la base de datos (borra todos los datos)
docker-compose down -v

# Ver logs en tiempo real
docker-compose logs -f

# Entrar al contenedor de PHP
docker-compose exec app bash
```

### Artisan

```bash
# Ejecutar cualquier comando artisan
docker-compose exec app php artisan <comando>

# Resetear y volver a correr todas las migraciones
docker-compose exec app php artisan migrate:fresh

# Resetear migraciones y correr seeders
docker-compose exec app php artisan migrate:fresh --seed

# Ver estado de las migraciones
docker-compose exec app php artisan migrate:status

# Limpiar cache de configuracion
docker-compose exec app php artisan config:clear

# Hacer admin a un usuario existente
docker-compose exec app php artisan user:set-admin correo@ejemplo.com
```

### Tinker (consola interactiva)

```bash
docker-compose exec app php artisan tinker
```

Ejemplos dentro de Tinker:

```php
// Ver todos los usuarios
User::all();

// Crear un usuario manualmente
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
]);

// Salir
exit
```

---

## Estructura de contenedores

| Contenedor | Imagen | Puerto | Funcion |
|------------|--------|--------|---------|
| laravel_app | php:8.3-fpm | 9000 (interno) | PHP y logica de Laravel |
| laravel_nginx | nginx:alpine | 8080 -> 80 | Servidor web |
| laravel_db | mysql:8.0 | 3307 -> 3306 | Base de datos |

El puerto de MySQL hacia el exterior es `3307` para no colisionar con instalaciones locales. La comunicacion entre contenedores usa el puerto `3306` internamente.

---

## Estructura del proyecto relevante

```
crud-productos/
├── app/
│   ├── Http/
│   │   ├── Controllers/ProductController.php
│   │   └── Requests/StoreProductRequest.php
│   ├── Models/
│   │   ├── Product.php
│   │   └── User.php
│   └── Policies/ProductPolicy.php
├── database/
│   ├── migrations/
│   └── seeders/RolesSeeder.php
├── routes/
│   ├── web.php
│   └── api.php
├── docker/
│   ├── nginx/default.conf
│   └── php/local.ini
├── Dockerfile
├── docker-compose.yml
└── .env
```

---

## Notas importantes

**Migraciones duplicadas:** Si instalas Jetstream en un proyecto nuevo, no crees manualmente las migraciones de `two_factor` ni `teams`. Jetstream ya las publica automaticamente con `php artisan jetstream:install`.

**Datos al resetear:** Usar `migrate:fresh` o `docker-compose down -v` borra todos los datos. Siempre vuelve a correr el seeder despues de un reset.

**Productos sin propietario:** Si tienes productos sin `user_id` asignado, ejecuta en Tinker:

```php
DB::statement('UPDATE products SET user_id = 1 WHERE user_id IS NULL');
```

**Rutas protegidas:** Todas las rutas de productos estan dentro del middleware de autenticacion de Jetstream. Cualquier acceso sin sesion activa redirige al login.
