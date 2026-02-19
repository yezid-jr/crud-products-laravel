# 🚀 Guía de Extensión - Sistema de Roles

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

## Próximas Funcionalidades Sugeridas

- 🔔 Sistema de notificaciones cuando admin accede a productos
- 📊 Dashboard de estadísticas por rol
- 🏷️ Etiquetas y categorías
- ⭐ Sistema de ratings
- 💬 Comentarios en productos
- 📤 Exportar a PDF/Excel
- 🔍 Búsqueda avanzada
- 🔐 Historial de cambios (audit trail)
- 📱 API REST para roles
- 🎭 Cambios de rol temporales

