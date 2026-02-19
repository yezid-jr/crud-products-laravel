<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Establecer roles correctos
\App\Models\User::where('email', 'admin@example.com')->update(['role' => 'admin']);
\App\Models\User::where('email', 'test@example.com')->update(['role' => 'user']);
\App\Models\User::where('email', 'john@example.com')->update(['role' => 'user']);
\App\Models\User::where('email', 'andres@andres.com')->update(['role' => 'user']);

echo "✅ Roles actualizados correctamente\n";
