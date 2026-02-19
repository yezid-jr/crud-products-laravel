<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Actualizar usuarios existentes con roles
        $users = User::all();
        
        foreach ($users as $index => $user) {
            if ($index === 0) {
                // Primer usuario es admin
                $user->update(['role' => 'admin']);
            } else {
                $user->update(['role' => 'user']);
            }
        }

        $this->command->info('Roles asignados exitosamente!');
        
        // Mostrar usuarios
        foreach (User::all() as $user) {
            $this->command->line("- {$user->name} ({$user->email}) - Role: {$user->role}");
        }
    }
}
