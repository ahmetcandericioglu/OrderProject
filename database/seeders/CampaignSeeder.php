<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = [
            [
                'name' => 'Sabahattin Ali Romanlarında 2 Al 1 Öde',
                'type' => 'btgo',
                'conditions' => [
                    'author' => 'Sabahattin Ali',
                    'category_id' => 1,
                    'min_quantity' => 2,
                    'max_free_items' => 1
                ],
                'discount_rate' => null
            ],
            [
                'name' => 'Yerli Yazar Kitaplarında %5 İndirim',
                'type' => 'local_discount',
                'conditions' => [
                    'local_author' => true,
                    'discount_rate' => 0.05
                ],
                'discount_rate' => 0.05
            ],
            [
                'name' => '200 TL ve Üzeri Alışverişlerde %5 İndirim',
                'type' => 'total_discount',
                'conditions' => [
                    'min_order_amount' => 200,
                    'discount_rate' => 0.05
                ],
                'discount_rate' => 0.05
            ],
        ];

        foreach ($campaigns as $campaign) {
            Campaign::create($campaign);
        }
    }
}
