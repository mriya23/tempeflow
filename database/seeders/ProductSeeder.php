<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        $rows = [
            [
                'id' => 1,
                'title' => 'Tempe Mendoan',
                'desc' => 'Tempe medoan khas dengan cita rasa tradisional, cocok untuk mendoan.',
                'price' => 3000,
                'tag' => 'Tempe Daun Pisang',
                'img_path' => 'images/tempe_mendoan.png',
                'badge' => 'Best Seller',
                'released_at' => '2025-12-01',
                'popularity' => 99,
                'is_active' => 1,
            ],
            [
                'id' => 2,
                'title' => 'Tempe Biasa Daun',
                'desc' => 'Tempe tradisional dengan kemasan daun pisang, aroma khas dan rasa autentik.',
                'price' => 2500,
                'tag' => 'Tempe Daun Pisang',
                'img_path' => 'images/tempe_biasa_daun.png',
                'badge' => null,
                'released_at' => '2025-12-01',
                'popularity' => 95,
                'is_active' => 1,
            ],
            [
                'id' => 3,
                'title' => 'Tempe Biasa Daun Panjang',
                'desc' => 'Tempe ukuran panjang dengan kemasan daun pisang, cocok untuk kebutuhan keluarga.',
                'price' => 6000,
                'tag' => 'Tempe Daun Pisang',
                'img_path' => 'images/tempe_daun_panjang.png',
                'badge' => null,
                'released_at' => '2025-12-01',
                'popularity' => 88,
                'is_active' => 1,
            ],
            [
                'id' => 4,
                'title' => 'Tempe Biasa Plastik Panjang',
                'desc' => 'Tempe ukuran panjang dengan kemasan plastik food-grade, higienis dan tahan lama.',
                'price' => 6000,
                'tag' => 'Tempe Plastik',
                'img_path' => 'images/tempe_plastik_panjang.png',
                'badge' => null,
                'released_at' => '2025-12-01',
                'popularity' => 85,
                'is_active' => 1,
            ],
        ];

        foreach ($rows as $row) {
            $now = now();
            DB::table('products')->updateOrInsert(
                ['id' => (int) $row['id']],
                array_merge($row, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
