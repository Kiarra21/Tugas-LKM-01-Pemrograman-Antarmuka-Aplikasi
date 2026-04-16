<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['name' => 'Kiarra', 'email' => 'kiarra@example.com', 'bio' => 'Penulis utama pada topik pengembangan aplikasi dan dokumentasi API.'],
            ['name' => 'Farel', 'email' => 'farel@example.com', 'bio' => 'Penulis yang fokus pada backend, database, dan integrasi sistem.'],
            ['name' => 'Andi Pratama', 'email' => 'andi.pratama@example.com', 'bio' => 'Penulis teknologi backend dan cloud.'],
            ['name' => 'Siti Rahma', 'email' => 'siti.rahma@example.com', 'bio' => 'Berfokus pada data engineering dan analytics.'],
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@example.com', 'bio' => 'Praktisi software architecture.'],
        ];

        foreach ($authors as $author) {
            Author::updateOrCreate(
                ['email' => $author['email']],
                ['name' => $author['name'], 'bio' => $author['bio']]
            );
        }
    }
}
