<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'desc',
        'price',
        'tag',
        'img_path',
        'badge',
        'released_at',
        'popularity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'released_at' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
