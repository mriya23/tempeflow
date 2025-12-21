<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        if (Schema::hasColumn('orders', 'shipping_address')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->string('recipient_name')->nullable()->after('user_id');
            $table->string('recipient_phone')->nullable()->after('recipient_name');
            $table->text('shipping_address')->nullable()->after('recipient_phone');
            $table->string('shipping_city')->nullable()->after('shipping_address');
            $table->string('shipping_postal_code')->nullable()->after('shipping_city');
            $table->text('shipping_note')->nullable()->after('shipping_postal_code');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_name',
                'recipient_phone',
                'shipping_address',
                'shipping_city',
                'shipping_postal_code',
                'shipping_note',
            ]);
        });
    }
};
