<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (!(Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')) {
            return redirect()->route('home');
        }

        $q = trim((string) $request->query('q', ''));
        $sortBy = $request->query('sort', 'latest'); // latest, orders, spending

        $usersQuery = User::query()
            ->where(function ($q) {
                $q->whereNull('role')
                    ->orWhere('role', '!=', 'admin');
            })
            ->withCount('orders')
            ->withSum(['orders as total_spending' => function ($query) {
                $query->where('status', 'Selesai');
            }], 'grand_total');

        if ($q !== '') {
            $usersQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%' . $q . '%')
                    ->orWhere('email', 'like', '%' . $q . '%');
            });
        }

        // Sorting
        switch ($sortBy) {
            case 'orders':
                $usersQuery->orderByDesc('orders_count');
                break;
            case 'spending':
                $usersQuery->orderByDesc('total_spending');
                break;
            case 'name':
                $usersQuery->orderBy('name');
                break;
            case 'latest':
            default:
                $usersQuery->latest();
                break;
        }

        $users = $usersQuery->paginate(15)->withQueryString();

        // Stats
        $totalCustomers = User::query()
            ->where(function ($q) {
                $q->whereNull('role')->orWhere('role', '!=', 'admin');
            })
            ->count();
        $customersWithOrders = User::query()
            ->where(function ($q) {
                $q->whereNull('role')->orWhere('role', '!=', 'admin');
            })
            ->whereHas('orders')
            ->count();
        $newCustomersThisMonth = User::query()
            ->where(function ($q) {
                $q->whereNull('role')->orWhere('role', '!=', 'admin');
            })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => [
                'q' => $q,
                'sort' => $sortBy,
            ],
            'stats' => [
                'total_customers' => $totalCustomers,
                'customers_with_orders' => $customersWithOrders,
                'new_this_month' => $newCustomersThisMonth,
            ],
        ]);
    }

    public function show(Request $request, User $user): View|RedirectResponse
    {
        if (!(Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')) {
            return redirect()->route('home');
        }

        // Load user orders with items
        $orders = Order::query()
            ->where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->paginate(10);

        // Calculate stats for this user
        $totalOrders = Order::query()->where('user_id', $user->id)->count();
        $completedOrders = Order::query()->where('user_id', $user->id)->where('status', 'Selesai')->count();
        $totalSpending = (int) Order::query()->where('user_id', $user->id)->where('status', 'Selesai')->sum('grand_total');
        $cancelledOrders = Order::query()->where('user_id', $user->id)->where('status', 'Dibatalkan')->count();

        // Most ordered products by this user
        $favoriteProducts = Order::query()
            ->where('user_id', $user->id)
            ->where('status', 'Selesai')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('order_items.product_title, order_items.product_id, SUM(order_items.qty) as total_qty')
            ->groupBy('order_items.product_title', 'order_items.product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('admin.users.show', [
            'user' => $user,
            'orders' => $orders,
            'stats' => [
                'total_orders' => $totalOrders,
                'completed_orders' => $completedOrders,
                'total_spending' => $totalSpending,
                'cancelled_orders' => $cancelledOrders,
            ],
            'favorite_products' => $favoriteProducts,
        ]);
    }
}
