# CRUD de Productos con Laravel 12 + MySQL + Tailwind CSS

Este proyecto consiste en la creación de un **CRUD (Create, Read, Update, Delete)** de productos utilizando **Laravel 12**, **MySQL**, **Tailwind CSS** y el patrón **MVC**.

---

## 1. Requisitos del sistema

Antes de iniciar, es necesario contar con:

* Sistema operativo Windows
* Conexión a internet
* Permisos para instalar software

---

## 2. Instalación de Laragon

Laragon es un entorno de desarrollo local que incluye:

* PHP
* MySQL
* Apache
* Composer

### Pasos:

1. Descargar Laragon desde: [https://laragon.org/](https://laragon.org/)
2. Ejecutar el instalador
3. Completar la instalación con las opciones por defecto
4. Abrir Laragon
5. Presionar el botón **Start All**

Cuando Laragon está corriendo correctamente:

* Apache aparece en color verde
* MySQL aparece en color verde

Laragon se ejecuta en segundo plano (bandeja del sistema).

---

## 3. Verificación y conexión a MySQL

### Uso de HeidiSQL (incluido en Laragon)

1. Abrir Laragon
2. Click en **Database**
3. Se abre HeidiSQL automáticamente
4. Configuración:

   * Host: `127.0.0.1`
   * Usuario: `root`
   * Contraseña: (vacía)
   * Puerto: `3306`
5. Click en **Open**

Si conecta correctamente, MySQL está funcionando.

---

## 4. Creación de la base de datos

En HeidiSQL:

1. Click derecho sobre `Laragon.MySQL`
2. Create new → Database
3. Nombre:

```text
crud_productos
```

Esta base de datos será usada por Laravel.

---

## 5. Instalación de Laravel 12

Antes de continuar hay que ver que el entorno de Laragon no es global, se puede trabajar así? sí, pero es muy engorroso, mi solución fué y la recomiendo, es agregar el PHP y el Composer al PATH de Windows.

### Pasos:

1. Windows → Variables de entorno

2. En Path (variables del sistema) → Editar

3. Agregar la ruta del PHP de Laragon y luego la ruta de Composer
   *ejemplo:*
   > C:\laragon\bin\php\php-8.x.x
   > C:\laragon\bin\composer

4. Aceptar todo

Luego prueba en la terminal:
```bash
php -v
composer -V
```

### Alternativa (si no quieres tocar PATH)

En VS Code puedes:

• Abrir terminal integrada

• Cambiarla a CMD

• Ejecutar todo desde la terminal de Laragon aparte

Funciona, pero es menos cómodo.

---

Abrir **CMD o PowerShell** y ejecutar:

```bash
composer create-project laravel/laravel crud-productos
```

Ingresar al proyecto:

```bash
cd crud-productos
```

Levantar el servidor:

```bash
php artisan serve
```

Acceder en el navegador:

```text
http://127.0.0.1:8000
```

---

## 6. Configuración del archivo `.env`

El archivo `.env` contiene la configuración del proyecto.

Modificar las variables de base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crud_productos
DB_USERNAME=root
DB_PASSWORD=
```

Limpiar caché de configuración:

```bash
php artisan config:clear
```

---

Compilar estilos y dependencias:

```bash
npm run dev
```

---

## 7. Creación del MVC del Producto

Comando usado:

```bash
php artisan make:model Product -mcr
```

Esto genera:

* Modelo: `Product`
  * Representa la tabla products en la base de datos.
* `-m` Migración `database/migrations/xxxx_xx_xx_create_products_table.php`
  * Sirve para crear la tabla products.
* `-c` Controlador: `ProductController`
  * Controlador para manejar la lógica del CRUD.
* `-r` → Resource Controller

  * Hace que el controlador tenga estos métodos listos:

    **index()**   // listar
    **create()**  // formulario crear
    **store()**   // guardar
    **show()**    // mostrar uno
    **edit()**    // formulario editar
    **update()**  // actualizar
    **destroy()** // eliminar

---

## 8. Migración de la tabla productos

Archivo de migración:

```php
$table->id();
$table->string('name');
$table->text('description');
$table->decimal('price', 10, 2);
$table->timestamps();
```

Ejecutar migración:

```bash
php artisan migrate
```

---

## 9. FormRequest (Validaciones)

Creación:

```bash
php artisan make:request StoreProductRequest
```

Reglas:

```php
'name' => 'required|min:3',
'description' => 'required',
'price' => 'required|numeric|min:0'
```

Esto garantiza validación limpia y organizada.

---

## 10 Rutas del CRUD

Archivo `routes/web.php`:

```php
Route::resource('products', ProductController::class);
```

Esto crea automáticamente todas las rutas CRUD.

---

## 11. Vistas

Rutas:

* `products/index.blade.php` → listado paginado
* `products/create.blade.php` → crear producto
* `products/edit.blade.php` → editar producto
* `products/show.blade.php` → ver detalle

---

---

**Autor:** Yesid Castro
**Tecnologías:** Laravel 12 · MySQL · Tailwind CSS

controller
@forelse

app/Http/Requests/StoreProductRequest.php

Debe tener:

public function authorize(): bool
{
    return true;
}


(Si no, Laravel bloqueará el formulario)

php artisan migrate:fresh --seed


php artisan config:clear
php artisan route:clear
php artisan storage:link
php artisan cache:clear
php artisan view:clear


# Guía de Extensión - Sistema de Roles

## Cómo Agregar Más Roles

### 1. Modificar la Migración de Roles

Edita `database/migrations/2026_01_29_000001_add_role_to_users_table.php`:

```php
$table->enum('role', ['admin', 'user', 'moderator', 'editor'])
      ->default('user')
      ->after('email');
```

Luego ejecuta:
```bash
php artisan migrate:rollback --step=2
php artisan migrate
```

### 2. Actualizar la Policy

Edita `app/Policies/ProductPolicy.php`:

```php
public function viewAny(User $user): bool
{
    return true; // Todos pueden listar
}

public function view(User $user, Product $product): bool
{
    // Admin siempre puede, usuarios solo sus propios, moderadores ven todos
    if ($user->isAdmin() || $user->role === 'moderator') {
        return true;
    }
    return $user->id === $product->user_id;
}
```

### 3. Agregar Método al Modelo User

```php
// app/Models/User.php

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isModerator(): bool
{
    return $this->role === 'moderator';
}

public function can($action): bool
{
    // Lógica personalizada
    return true;
}
```

---

## Agregar Más Columnas de Metadata

### 1. Crear una Migración

```bash
php artisan make:migration add_metadata_to_products_table
```

```php
Schema::table('products', function (Blueprint $table) {
    $table->string('category')->nullable();
    $table->json('tags')->nullable();
    $table->boolean('is_published')->default(false);
    $table->timestamp('published_at')->nullable();
    $table->softDeletes();
});
```

### 2. Actualizar el Modelo

```php
protected $fillable = [
    'name',
    'description',
    'price',
    'user_id',
    'category',
    'tags',
    'is_published',
    'published_at',
];

protected function casts(): array
{
    return [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];
}
```

---

## Sistema Avanzado: Permisos Granulares

### 1. Crear Tabla de Permisos

```bash
php artisan make:migration create_permissions_table
```

```php
Schema::create('permissions', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('description')->nullable();
    $table->timestamps();
});

Schema::create('role_permission', function (Blueprint $table) {
    $table->id();
    $table->foreignId('role_id')->constrained();
    $table->foreignId('permission_id')->constrained();
    $table->unique(['role_id', 'permission_id']);
});
```

### 2. Usar Permisos en la Policy

```php
public function update(User $user, Product $product): bool
{
    if ($user->isAdmin()) {
        return true;
    }
    
    // Verificar permiso granular
    return $user->hasPermission('edit_product') && 
           $user->id === $product->user_id;
}
```

---

## Auditoría de Cambios

### 1. Agregar Tabla de Auditoría

```bash
php artisan make:migration create_activity_logs_table
```

```php
Schema::create('activity_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('model_type');
    $table->bigInteger('model_id');
    $table->string('action'); // created, updated, deleted
    $table->json('changes')->nullable();
    $table->timestamps();
    $table->index(['model_type', 'model_id']);
});
```

### 2. Usar Event Listeners

```bash
php artisan make:listener LogProductChanges
```

```php
<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogProductChanges
{
    public function handle($event)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'model_type' => get_class($event->model),
            'model_id' => $event->model->id,
            'action' => $event->model->wasRecentlyCreated ? 'created' : 'updated',
            'changes' => $event->model->getChanges(),
        ]);
    }
}
```

---

## Filtros Avanzados

### 1. Scope para Productos Propios

```php
// app/Models/Product.php

public function scopeOwnedBy($query, User $user)
{
    if ($user->isAdmin()) {
        return $query;
    }
    
    return $query->where('user_id', $user->id);
}
```

Uso en controlador:
```php
$products = Product::ownedBy(auth()->user())->paginate(5);
```

### 2. Scope para Filtrar por Estado

```php
public function scopePublished($query)
{
    return $query->where('is_published', true);
}

public function scopeRecent($query, $days = 7)
{
    return $query->where('created_at', '>=', now()->subDays($days));
}
```

---

## Testing

### 1. Test de Autorización

```bash
php artisan make:test ProductAuthorizationTest
```

```php
<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductAuthorizationTest extends TestCase
{
    public function test_user_can_only_see_own_products()
    {
        $user1 = User::factory()->create(['role' => 'user']);
        $user2 = User::factory()->create(['role' => 'user']);
        
        $product1 = Product::factory()->create(['user_id' => $user1->id]);
        $product2 = Product::factory()->create(['user_id' => $user2->id]);
        
        $this->actingAs($user1)
            ->get(route('products.index'))
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);
    }

    public function test_admin_can_see_all_products()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);
        
        $product = Product::factory()->create(['user_id' => $user->id]);
        
        $this->actingAs($admin)
            ->get(route('products.index'))
            ->assertSee($product->name);
    }
}
```

---

## Comandos Útiles

```bash
# Ver todos los usuarios
php artisan tinker
User::all()

# Cambiar rol de un usuario
php artisan user:set-admin usuario@email.com
php artisan user:set-user usuario@email.com  # si lo agregas

# Ver logs de auditoría
ActivityLog::latest()->limit(10)->get()

# Resetear datos (BE CAREFUL!)
php artisan migrate:refresh --seed
```

---

# Sistema de Roles - CRUD de Productos

## Cambios Implementados

### 1. **Migraciones Creadas**
- **`2026_01_29_000001_add_role_to_users_table.php`**: Agrega el campo `role` (enum: 'admin', 'user') a la tabla `users`
- **`2026_01_29_000002_add_user_id_to_products_table.php`**: Agrega la relación `user_id` a la tabla `products`

### 2. **Modelos Actualizados**

#### `User.php`
```php
// Método para verificar si es admin
public function isAdmin(): bool

// Relación con productos
public function products(): HasMany
```

#### `Product.php`
```php
// Relación con usuario
public function user(): BelongsTo
```

### 3. **Policy Creada** (`ProductPolicy.php`)
- **viewAny()**: Todos pueden listar productos
- **view()**: Admin ve todos, usuarios solo sus propios
- **create()**: Todos pueden crear
- **update()**: Admin o propietario puede editar
- **delete()**: Admin o propietario puede eliminar

### 4. **Controlador Actualizado** (`ProductController.php`)
- **index()**: Filtra productos según el rol
  - Admin: ve todos los productos
  - Usuario: ve solo sus productos
- **store()**: Asigna automáticamente el `user_id`
- **show/edit/destroy()**: Usa las policies para autorizar

### 5. **Vistas Actualizadas**
- **index.blade.php**: 
  - Admin ve columna adicional "Propietario"
  - Usuarios no ven esta columna

### 6. **Seeders**
- **RolesSeeder.php**: Asigna roles a usuarios existentes
- Comando artisan: `php artisan user:set-admin {email}`

---

## Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@example.com | password | **Admin** |
| test@example.com | password | Usuario |
| john@example.com | password | Usuario |

---

## Funcionalidades

### Para **Administradores**:
✅ Ver todos los productos de todos los usuarios  
✅ Ver quién es el propietario de cada producto  
✅ Editar y eliminar cualquier producto  
✅ Crear productos propios  

### Para **Usuarios**:
✅ Ver solo sus propios productos  
✅ Crear nuevos productos  
✅ Editar y eliminar solo sus productos  
❌ No pueden ver productos de otros usuarios  

---

## Comandos Útiles

### Hacer un usuario administrador:
```bash
php artisan user:set-admin {email@example.com}
```

### Ver estados de migraciones:
```bash
php artisan migrate:status
```

### Ejecutar seeders:
```bash
php artisan db:seed --class=RolesSeeder
```

---

## Notas Importantes

1. **Todos los productos anteriores** necesitan tener un `user_id` asignado
2. Si hay productos sin `user_id`, asígnalos manualmente:
   ```sql
   UPDATE products SET user_id = 1 WHERE user_id IS NULL;
   ```

3. El sistema usa **policies de Laravel** para controlar acceso
4. La autorización se valida en:
   - Ver detalles (`show`)
   - Editar (`edit`/`update`)
   - Eliminar (`destroy`)

5. El filtrado en `index()` se hace a nivel de modelo para mejor rendimiento

---

## Flujo de Autenticación

```
Usuario No Autenticado
        ↓
    Login ✓
        ↓
Asignado Role (admin/user)
        ↓
    View Index
        ↓
    ├─ Si es ADMIN → Ve todos los productos + propietario
    └─ Si es USER → Ve solo sus productos
```

---


Docker comandos

agrega los roles a los usuarios creados, el primer usuario lo coloca como admin

docker-compose exec app php artisan db:seed --class=RolesSeeder

levantar la app
docker-compose up -d --build

bajar la app
docker-compose down -v

migraciones:
docker-compose exec app php artisan migrate:fresh

eliminar migraciones ejemplo:
docker-compose exec app rm database/migrations/2026_01_28_151235_create_team_user_table.php

# Construir e iniciar los contenedores
docker-compose up -d --build

# Ejecutar migraciones
docker-compose exec app php artisan migrate

# (Opcional) Si tienes seeders
docker-compose exec app php artisan db:seed

# Generar app key si no tienes una
docker-compose exec app php artisan key:generate

# Ver logs en tiempo real
docker-compose logs -f

# Entrar al contenedor de la app
docker-compose exec app bash

# Correr artisan desde fuera del contenedor
docker-compose exec app php artisan <comando>

# Detener todo
docker-compose down

# Detener y eliminar volúmenes (borra la BD)
docker-compose down -v