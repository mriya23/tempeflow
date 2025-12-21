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

        if (Schema::hasColumn('orders', 'snap_token')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_provider')->nullable()->after('grand_total');
            $table->string('payment_status')->nullable()->after('payment_provider');
            $table->text('snap_token')->nullable()->after('payment_status');
            $table->string('midtrans_transaction_id')->nullable()->after('snap_token');
            $table->timestamp('paid_at')->nullable()->after('midtrans_transaction_id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_provider',
                'payment_status',
                'snap_token',
                'midtrans_transaction_id',
                'paid_at',
            ]);
        });
    }
};
