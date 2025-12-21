<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_admin_update_status_and_tracking_reflects(): void
    {
        if (!in_array('sqlite', \PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('PDO SQLite driver not available. Enable pdo_sqlite/sqlite3 or configure a dedicated testing database.');
        }

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->withSession(['cart' => [101 => 1, 201 => 2]])
            ->post('/checkout');

        $response->assertRedirect();

        $order = Order::query()->where('user_id', (int) $user->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertSame('Dikemas', $order->status);
        $this->assertSame(2, $order->items()->count());

        $admin = User::factory()->create([
            'email' => 'admin@tempeflow.test',
        ]);

        $update = $this
            ->from('/admin/dashboard')
            ->actingAs($admin)
            ->post(route('admin.orders.updateStatus', ['order' => $order->id]), [
                'status' => 'Dikirim',
            ]);

        $update
            ->assertSessionHasNoErrors()
            ->assertSessionHas('admin_notice')
            ->assertRedirect('/admin/dashboard');

        $order->refresh();
        $this->assertSame('Dikirim', $order->status);

        $tracking = $this
            ->actingAs($user)
            ->get('/lacak-pesanan?order_code='.$order->code);

        $tracking
            ->assertOk()
            ->assertSee($order->code)
            ->assertSee('Dikirim');

        $myOrders = $this
            ->actingAs($user)
            ->get('/pesanan-saya');

        $myOrders
            ->assertOk()
            ->assertSee($order->code)
            ->assertSee('Dikirim');

        $adminDashboard = $this
            ->actingAs($admin)
            ->get('/admin/dashboard');

        $adminDashboard
            ->assertOk()
            ->assertSee($order->code)
            ->assertSee('Dikirim');
    }
}
