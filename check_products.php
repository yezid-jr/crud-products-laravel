<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$products = \App\Models\Product::all();
echo "Total de productos: " . $products->count() . "\n";

if ($products->count() > 0) {
    echo "\nProductos:\n";
    foreach ($products as $p) {
        echo sprintf("ID: %d, Name: %s, User ID: %s\n", 
            $p->id, 
            $p->name, 
            $p->user_id ?? 'NULL'
        );
    }
    
    // Asignar productos sin user_id al primer usuario
    $productsWithoutUser = \App\Models\Product::whereNull('user_id')->get();
    if ($productsWithoutUser->count() > 0) {
        echo "\n⚠️  Encontrados " . $productsWithoutUser->count() . " productos sin user_id\n";
        echo "Asignando al usuario 1...\n";
        \App\Models\Product::whereNull('user_id')->update(['user_id' => 1]);
        echo "✓ Completado\n";
    }
} else {
    echo "No hay productos aún\n";
}
