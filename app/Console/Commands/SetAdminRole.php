<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetAdminRole extends Command
{
    protected $signature = 'user:set-admin {email}';
    protected $description = 'Set a user as admin by email';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuario con email {$email} no encontrado");
            return 1;
        }

        $user->update(['role' => 'admin']);
        $this->info("✓ {$user->name} ({$email}) ahora es administrador");
        return 0;
    }
}
