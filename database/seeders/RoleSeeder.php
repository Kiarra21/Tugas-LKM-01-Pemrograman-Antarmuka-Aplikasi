<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(
            ['name' => 'admin'],
            ['description' => 'Can perform full CRUD operations']
        );

        Role::updateOrCreate(
            ['name' => 'user'],
            ['description' => 'Can only read books and borrowings']
        );
    }
}
