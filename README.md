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



