<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class CatalogService
{
    public function all(): array
    {
        if (Schema::hasTable('products')) {
            $rows = Product::query()
                ->where('is_active', true)
                ->orderByDesc('popularity')
                ->get();

            return $rows->map(function (Product $p) {
                return [
                    'id' => (int) $p->id,
                    'title' => (string) $p->title,
                    'desc' => (string) $p->desc,
                    'price' => (int) $p->price,
                    'tag' => (string) $p->tag,
                    'img' => asset((string) $p->img_path),
                    'released_at' => ($p->released_at ? $p->released_at->toDateString() : null),
                    'popularity' => (int) $p->popularity,
                ];
            })->values()->all();
        }

        return [
            [
                'id' => 101,
                'title' => 'Tempe Plastik Premium 500gr',
                'desc' => 'Kemasan plastik food-grade, higienis dan praktis untuk harian.',
                'price' => 20000,
                'tag' => 'Tempe Plastik',
                'img' => asset('images/tempeplastik_katalog.png'),
                'released_at' => '2025-11-20',
                'popularity' => 95,
            ],
            [
                'id' => 102,
                'title' => 'Tempe Plastik Ekonomis 300gr',
                'desc' => 'Pilihan hemat dengan kualitas tetap terjaga, cocok untuk reseller.',
                'price' => 15000,
                'tag' => 'Tempe Plastik',
                'img' => asset('images/tempeplastik_katalog.png'),
                'released_at' => '2025-12-05',
                'popularity' => 82,
            ],
            [
                'id' => 201,
                'title' => 'Tempe Daun Pisang Tradisional 400gr',
                'desc' => 'Tempe dengan aroma khas daun pisang, rasa autentik dan sehat.',
                'price' => 18000,
                'tag' => 'Tempe Daun Pisang',
                'img' => asset('images/tempedaunpisang_katalog.png'),
                'released_at' => '2025-10-10',
                'popularity' => 75,
            ],
            [
                'id' => 202,
                'title' => 'Tempe Daun Pisang Premium 600gr',
                'desc' => 'Tempe premium dengan fermentasi sempurna, cocok untuk reseller.',
                'price' => 24000,
                'tag' => 'Tempe Daun Pisang',
                'img' => asset('images/tempedaunpisang_katalog.png'),
                'released_at' => '2025-12-12',
                'popularity' => 88,
            ],
            [
                'id' => 203,
                'title' => 'Tempe Daun Pisang Mini 250gr',
                'desc' => 'Ukuran pas untuk kebutuhan rumah tangga.',
                'price' => 12000,
                'tag' => 'Tempe Daun Pisang',
                'img' => asset('images/tempedaunpisang_katalog.png'),
                'released_at' => '2025-12-15',
                'popularity' => 99,
            ],
            [
                'id' => 301,
                'title' => 'Paket Grosir Tempe Daun 3kg',
                'desc' => 'Paket grosir untuk pasar dan reseller dengan harga terbaik.',
                'price' => 85000,
                'tag' => 'Grosir',
                'img' => asset('images/tempedaun3kg_katalog.png'),
                'released_at' => '2025-09-01',
                'popularity' => 60,
            ],
        ];
    }

    public function keyById(): array
    {
        $byId = [];
        foreach ($this->all() as $p) {
            $id = (int) ($p['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $byId[$id] = $p;
        }

        return $byId;
    }

    public function find(int $productId): ?array
    {
        $all = $this->keyById();
        return $all[$productId] ?? null;
    }

    public function filterAndSort(string $kategori, string $sort): array
    {
        $products = $this->all();

        $filterMap = [
            'semua' => null,
            'plastik' => 'Tempe Plastik',
            'daun' => 'Tempe Daun Pisang',
        ];

        $filterTag = $filterMap[$kategori] ?? null;
        if ($filterTag) {
            $products = array_values(array_filter($products, fn ($p) => ($p['tag'] ?? null) === $filterTag));
        }

        if ($sort === 'populer') {
            usort($products, fn ($a, $b) => ($b['popularity'] ?? 0) <=> ($a['popularity'] ?? 0));
        } elseif ($sort === 'terbaru') {
            usort($products, fn ($a, $b) => (strtotime((string) ($b['released_at'] ?? '1970-01-01')) <=> strtotime((string) ($a['released_at'] ?? '1970-01-01'))));
        } elseif ($sort === 'harga_terendah') {
            usort($products, fn ($a, $b) => ($a['price'] ?? 0) <=> ($b['price'] ?? 0));
        } elseif ($sort === 'harga_tertinggi') {
            usort($products, fn ($a, $b) => ($b['price'] ?? 0) <=> ($a['price'] ?? 0));
        }

        return $products;
    }
}
