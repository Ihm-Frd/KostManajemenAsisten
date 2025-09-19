<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['name' => 'karyawan',    'guard_name' => 'web'],
            ['name' => 'penghuni',    'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
