<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'product_id' => 1,
                'title' => 'İnce Memed',
                'category_id' => 1,
                'category_title' => 'Roman',
                'author' => 'Yaşar Kemal',
                'list_price' => 48.75,
                'stock_quantity' => 10
            ],
            [
                'product_id' => 2,
                'title' => 'Tutunamayanlar',
                'category_id' => 1,
                'category_title' => 'Roman',
                'author' => 'Oğuz Atay',
                'list_price' => 90.3,
                'stock_quantity' => 20
            ],
            [
                'product_id' => 3,
                'title' => 'Kürk Mantolu Madonna',
                'category_id' => 1,
                'category_title' => 'Roman',
                'author' => 'Sabahattin Ali',
                'list_price' => 9.1,
                'stock_quantity' => 4
            ],
            [
                'product_id' => 4,
                'title' => 'Fareler ve İnsanlar',
                'category_id' => 1,
                'category_title' => 'Roman',
                'author' => 'John Steinback',
                'list_price' => 35.75,
                'stock_quantity' => 8
            ],
            [
                'product_id' => 5,
                'title' => 'Şeker Portakalı',
                'category_id' => 1,
                'category_title' => 'Roman',
                'author' => 'Jose Mauro De Vasconcelos',
                'list_price' => 33,
                'stock_quantity' => 1
            ],
            [
                'product_id' => 6,
                'title' => 'Sen Yola Çık Yol Sana Görünür',
                'category_id' => 2,
                'category_title' => 'Kişisel Gelişim',
                'author' => 'Hakan Mengüç',
                'list_price' => 28.5,
                'stock_quantity' => 7
            ],
            [
                'product_id' => 7,
                'title' => 'Kara Delikler',
                'category_id' => 3,
                'category_title' => 'Bilim',
                'author' => 'Stephen Hawking',
                'list_price' => 39,
                'stock_quantity' => 2
            ],
            [
                'product_id' => 8,
                'title' => 'Allah De Ötesini Bırak',
                'category_id' => 4,
                'category_title' => 'Din Tasavvuf',
                'author' => 'Uğur Koşar',
                'list_price' => 39.6,
                'stock_quantity' => 18
            ],
            [
                'product_id' => 9,
                'title' => 'Aşk 5 Vakittir',
                'category_id' => 4,
                'category_title' => 'Din Tasavvuf',
                'author' => 'Mehmet Yıldız',
                'list_price' => 42,
                'stock_quantity' => 9
            ],
            [
                'product_id' => 10,
                'title' => 'Benim Zürafam Uçabilir',
                'category_id' => 7,
                'category_title' => 'Çocuk ve Gençlik',
                'author' => 'Mert Arık',
                'list_price' => 27.3,
                'stock_quantity' => 12
            ],
            [
                'product_id' => 11,
                'title' => 'Kuyucaklı Yusuf',
                'category_id' => 1,
                'category_title' => 'Roman',
                'author' => 'Sabahattin Ali',
                'list_price' => 10.4,
                'stock_quantity' => 2
            ],
            [
                'product_id' => 12,
                'title' => 'Kamyon - Seçme Öyküler',
                'category_id' => 5,
                'category_title' => 'Öykü',
                'author' => 'Sabahattin Ali',
                'list_price' => 9.75,
                'stock_quantity' => 9
            ],
            [
                'product_id' => 13,
                'title' => 'Kendime Düşünceler',
                'category_id' => 6,
                'category_title' => 'Felsefe',
                'author' => 'Marcus Aurelius',
                'list_price' => 14.40,
                'stock_quantity' => 1
            ],
            [
                'product_id' => 14,
                'title' => 'Denemeler - Hasan Ali Yücel Klasikleri',
                'category_id' => 6,
                'category_title' => 'Felsefe',
                'author' => 'Michel de Montaigne',
                'list_price' => 24,
                'stock_quantity' => 4
            ],
            [
                'product_id' => 15,
                'title' => 'Animal Farm',
                'category_id' => 1,
                'category_title' => 'Roman',
                'author' => 'George Orwell',
                'list_price' => 17.50,
                'stock_quantity' => 1
            ],
            [
                'product_id' => 16,
                'title' => 'Dokuzuncu Hariciye Koğuşu',
                'category_id' => 1,
                'category_title' => 'Roman',
                'author' => 'Peyami Safa',
                'list_price' => 18.5,
                'stock_quantity' => 0
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
