<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'description' => 'Kategori untuk pengembangan website dan API.'],
            ['name' => 'Database', 'description' => 'Kategori untuk basis data dan query optimization.'],
            ['name' => 'Data Science', 'description' => 'Kategori untuk analisis data dan machine learning.'],
            ['name' => 'Mobile Development', 'description' => 'Kategori untuk pengembangan aplikasi mobile.'],
            ['name' => 'Software Engineering', 'description' => 'Kategori untuk arsitektur dan rekayasa perangkat lunak.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
