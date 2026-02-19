# 🎯 Guía de Prueba - Sistema de Roles

## 📍 Estado Actual

El sistema de roles está completamente implementado y listo para pruebas. Tienes:

- **1 Admin**: `admin@example.com`
- **2 Usuarios**: `test@example.com`, `andres@andres.com`

---

## 🧪 Cómo Probar

### **Prueba 1: Admin Ver Todos los Productos**

1. Login como **`admin@example.com`** (password: `password`)
2. Ve a `/products`
3. Verás una columna adicional "Propietario"
4. Aunque no haya productos aún, cuando crees algunos, verás todos

### **Prueba 2: Usuario Ver Solo Sus Productos**

1. Login como **`test@example.com`** (password: `password`)
2. Crea un producto nuevo:
   - Nombre: "Producto Prueba Test"
   - Descripción: "Mi primer producto"
   - Precio: 99.99
3. Logout

4. Login como **`andres@andres.com`** (password: `password`)
5. Ve a `/products`
6. **NO verás** el producto de test@example.com
7. Crea tu propio producto:
   - Nombre: "Producto Prueba Andres"
   - Descripción: "Mi producto personal"
   - Precio: 149.99

### **Prueba 3: Admin Ve Todo**

1. Login como **`admin@example.com`**
2. Ve a `/products`
3. **VÉS AMBOS PRODUCTOS** (de test y de andres)
4. Verás la columna "Propietario" mostrando quién creó cada uno

### **Prueba 4: Control de Acceso**

1. Login como **`test@example.com`**
2. Intenta editar/eliminar el producto de andres (si lo intentas por URL)
3. Deberías recibir un error **403 Unauthorized**

---

## 🔐 Verificación de Seguridad

### Filtrado en Vista
```
- Usuario normal: Ve solo SUS productos
- Admin: Ve TODOS los productos
```

### Filtrado en Base de Datos
```php
// En ProductController@index
if (auth()->user()->isAdmin()) {
    $products = Product::with('user')->paginate(5);
} else {
    $products = auth()->user()->products()->paginate(5);
}
```

### Autorización en Acciones
```php
// Antes de editar/eliminar
$this->authorize('update', $product);
$this->authorize('delete', $product);
```

---

## 🚀 Casos de Uso Avanzados

### Crear Múltiples Usuarios de Prueba

```bash
php artisan user:set-admin nuevo@example.com
```

Este comando:
1. Busca al usuario por email
2. Lo actualiza con rol 'admin'

### Ver Usuarios en BD

Consulta directa:
```sql
SELECT id, name, email, role FROM users;
```

### Filtrar Productos por Usuario

```bash
php artisan tinker
# Luego en la consola:
App\Models\User::find(1)->products  // Ver productos del usuario 1
```

---

## 📊 Flujo de Datos

```
┌─────────────────┐
│  Usuario Login  │
└────────┬────────┘
         │
    ┌────▼─────┐
    │ ¿Es Admin?│
    └────┬─────┘
         │
    ┌────┴──────────────────────┐
    │                           │
   YES                         NO
    │                           │
  ┌─▼──────────────┐    ┌──────▼────────────┐
  │ Ver TODOS los  │    │ Ver SOLO sus      │
  │ productos de   │    │ propios productos │
  │ todos usuarios │    │                   │
  └────────────────┘    └───────────────────┘
```

---

## 📝 Notas Importantes

1. **La contraseña de todos los usuarios es**: `password`
2. **Admin**: Ve columna "Propietario" en lista
3. **Usuarios normales**: No ven esa columna
4. **Al crear productos**: Se asigna automáticamente el user_id del usuario logueado
5. **La autorización se valida en**: show(), edit(), update(), destroy()

---

## ❌ Errores Comunes y Soluciones

### Error: "SQLSTATE[42S21]: Column already exists"
- Solución: Las migraciones ya se ejecutaron, no necesitas hacerlo de nuevo

### Error: "Call to undefined method isAdmin()"
- Solución: Verifica que User.php tenga el método `isAdmin()`

### Producto no se elimina
- Verifica que seas el propietario o admin
- Revisa la Policy en `app/Policies/ProductPolicy.php`

---

## 🎓 Conceptos Aprendidos

✅ **Migraciones**: Agregar columnas a tablas existentes  
✅ **Relaciones**: BelongsTo, HasMany entre modelos  
✅ **Policies**: Control de autorización en Laravel  
✅ **Scopes**: Filtrar consultas automáticamente  
✅ **Métodos de Modelo**: `isAdmin()`, relaciones  
✅ **Vistas Blade**: Condicionales con `@if`  

---

## 🔄 Próximos Pasos Opcionales

1. **Agregar más roles** (moderator, editor)
2. **Sistema de permisos granulares**
3. **Auditoría de cambios**
4. **Notificaciones**
5. **Dashboard de estadísticas**

Ver `EXTENSION_GUIDE.md` para más detalles.

---

## 📞 Ayuda Rápida

Ejecutar verification script:
```bash
php verify_setup.php
```

Ver usuarios en terminal:
```bash
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();
\\App\\Models\\User::all(['email', 'role'])->each(function(\$u) { 
    echo \$u->email . ': ' . \$u->role . PHP_EOL;
});
"
```

