<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id' => 1, 'title' => 'Roman'],
            ['id' => 2, 'title' => 'Kişisel Gelişim'],
            ['id' => 3, 'title' => 'Bilim'],
            ['id' => 4, 'title' => 'Din Tasavvuf'],
            ['id' => 5, 'title' => 'Öykü'],
            ['id' => 6, 'title' => 'Felsefe'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
