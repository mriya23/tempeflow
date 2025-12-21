<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tempeflow:smoke', function () {
    $this->info('Running TempeFlow smoke test (order flow)...');

    DB::beginTransaction();

    try {
        $userEmail = 'smoke-user+'.bin2hex(random_bytes(4)).'@tempeflow.test';
        $user = User::query()->create([
            'name' => 'Smoke User',
            'email' => $userEmail,
            'password' => Hash::make('password'),
        ]);

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@tempeflow.test'],
            [
                'name' => 'Smoke Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        $items = [
            ['id' => 101, 'title' => 'Tempe Plastik Premium 500gr', 'tag' => 'Tempe Plastik', 'price' => 20000, 'qty' => 1],
            ['id' => 201, 'title' => 'Tempe Daun Pisang Tradisional 400gr', 'tag' => 'Tempe Daun Pisang', 'price' => 18000, 'qty' => 2],
        ];

        $subtotal = 0;
        foreach ($items as $it) {
            $subtotal += ((int) $it['price'] * (int) $it['qty']);
        }

        do {
            $code = 'TF-'.strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
        } while (Order::query()->where('code', $code)->exists());

        $order = Order::query()->create([
            'code' => $code,
            'user_id' => (int) $user->id,
            'status' => 'Menunggu Pembayaran',
            'subtotal' => $subtotal,
            'discount' => 0,
            'grand_total' => $subtotal,
            'payment_provider' => 'midtrans',
            'payment_status' => 'pending',
        ]);

        foreach ($items as $it) {
            OrderItem::query()->create([
                'order_id' => (int) $order->id,
                'product_id' => (int) $it['id'],
                'product_title' => (string) $it['title'],
                'product_tag' => (string) $it['tag'],
                'price' => (int) $it['price'],
                'qty' => (int) $it['qty'],
                'subtotal' => ((int) $it['price'] * (int) $it['qty']),
            ]);
        }

        $order->load('items');

        if ($order->status !== 'Menunggu Pembayaran') {
            throw new \RuntimeException('Expected initial order status to be Menunggu Pembayaran.');
        }

        if ($order->items->count() !== 2) {
            throw new \RuntimeException('Expected 2 order items.');
        }

        $this->info('OK - Order created: '.$order->code.' (items: '.$order->items->count().')');

        $order->update([
            'payment_status' => 'paid',
            'status' => 'Dikemas',
            'paid_at' => now(),
        ]);
        $order->refresh();
        if ($order->status !== 'Dikemas') {
            throw new \RuntimeException('Expected order status to be Dikemas after payment.');
        }
        $this->info('OK - Payment simulated, status moved to Dikemas');

        $adminSees = Order::query()->with(['user', 'items'])->latest()->first();
        if (!$adminSees || $adminSees->id !== $order->id) {
            throw new \RuntimeException('Admin dashboard query did not return the created order.');
        }
        $this->info('OK - Admin query can see order');

        $order->update(['status' => 'Dikirim']);
        $order->refresh();
        if ($order->status !== 'Dikirim') {
            throw new \RuntimeException('Expected updated order status to be Dikirim.');
        }
        $this->info('OK - Status updated to Dikirim');

        $trackFound = Order::query()->with('items')->where('code', $order->code)->first();
        if (!$trackFound || $trackFound->status !== 'Dikirim') {
            throw new \RuntimeException('Tracking query did not reflect updated status.');
        }
        $this->info('OK - Tracking query reflects updated status');

        $myOrders = Order::query()->where('user_id', (int) $user->id)->where('code', $order->code)->first();
        if (!$myOrders || $myOrders->status !== 'Dikirim') {
            throw new \RuntimeException('My Orders query did not reflect updated status.');
        }
        $this->info('OK - My Orders query reflects updated status');

        $this->info('SUCCESS - Smoke test passed. Rolling back changes...');
    } catch (\Throwable $e) {
        DB::rollBack();
        $this->error('FAILED - '.$e->getMessage());
        throw $e;
    }

    DB::rollBack();
    return 0;
})->purpose('Smoke test checkout/admin/tracking flow (transactional, no data persisted)');
