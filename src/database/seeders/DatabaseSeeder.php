<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Buat role super_admin jika belum ada
        $role = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        // 2️⃣ Buat user admin jika belum ada
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'), // kasih default password
            ]
        );

        // 3️⃣ Assign role jika belum punya
        if (! $user->hasRole('super_admin')) {
            $user->assignRole($role);
        }

        // 4️⃣ Jalankan seeder lain
        $this->call([
            PasienSeeder::class,
        ]);
    }
}
