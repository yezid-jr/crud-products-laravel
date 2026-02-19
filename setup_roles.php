<?php

$host = '127.0.0.1';
$db   = 'crud_products';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Actualizar usuario existente a admin
    $pdo->exec("UPDATE users SET role = 'admin' WHERE email = 'admin@example.com'");
    
    // Crear usuario admin si no existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute(['admin@example.com']);
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO users (name, email, password, role, created_at, updated_at) 
                   VALUES ('Admin User', 'admin@example.com', '". bcrypt('password') ."', 'admin', NOW(), NOW())");
    }
    
    // Asegurar que los demás usuarios son tipo 'user'
    $pdo->exec("UPDATE users SET role = 'user' WHERE role IS NULL OR role = ''");

    echo "✓ Base de datos actualizada correctamente!\n";
    echo "✓ Usuarios disponibles:\n";
    echo "  - admin@example.com (Admin) - password: password\n";
    echo "  - test@example.com (User) - password: password\n";
    echo "  - john@example.com (User) - password: password\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
