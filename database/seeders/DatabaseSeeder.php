<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            AuthorSeeder::class,
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        User::updateOrCreate(
            ['email' => 'kiarra@example.com'],
            [
                'role_id' => $adminRole?->id,
                'name' => 'kiarra',
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'role_id' => $userRole?->id,
                'name' => 'Test User',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
