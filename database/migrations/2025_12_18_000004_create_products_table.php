<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            return;
        }

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('desc');
            $table->unsignedInteger('price');
            $table->string('tag');
            $table->string('img_path');
            $table->string('badge')->nullable();
            $table->date('released_at')->nullable();
            $table->unsignedInteger('popularity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tag', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
