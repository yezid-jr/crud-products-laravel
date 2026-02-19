# 🔐 Sistema de Roles - CRUD de Productos

## ✅ Cambios Implementados

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

## 👤 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@example.com | password | **Admin** |
| test@example.com | password | Usuario |
| john@example.com | password | Usuario |

---

## 🎯 Funcionalidades

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

## 🛠️ Comandos Útiles

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

## 📝 Notas Importantes

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

## 🔄 Flujo de Autenticación

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

## 🚀 Próximas Mejoras Posibles

- Agregar roles adicionales (moderador, editor)
- Sistema de permisos granulares
- Auditoría de cambios por usuario
- Soft deletes para productos
- Historial de cambios
- Notificaciones cuando un admin accede a tus productos

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