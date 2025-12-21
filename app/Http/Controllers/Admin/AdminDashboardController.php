<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (!(Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')) {
            return redirect()->route('home');
        }

        $q = trim((string) $request->query('q', ''));
        $status = trim((string) $request->query('status', ''));
        $dateFrom = $request->query('date_from', '');
        $dateTo = $request->query('date_to', '');

        // Basic stats
        $totalRevenue = (int) Order::query()->where('status', 'Selesai')->sum('grand_total');
        $totalOrders = (int) Order::query()->count();
        $ordersPending = (int) Order::query()->where('status', 'Menunggu Pembayaran')->count();
        $ordersPacked = (int) Order::query()->where('status', 'Dikemas')->count();
        $ordersShipped = (int) Order::query()->where('status', 'Dikirim')->count();
        $ordersDone = (int) Order::query()->where('status', 'Selesai')->count();
        $ordersCanceled = (int) Order::query()->where('status', 'Dibatalkan')->count();

        $todayOrders = (int) Order::query()->whereDate('created_at', today())->count();
        $todayRevenue = (int) Order::query()->where('status', 'Selesai')->whereDate('created_at', today())->sum('grand_total');

        $statusCounts = Order::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->map(fn ($v) => (int) $v)
            ->toArray();

        // Top 5 produk terlaris
        $topProducts = OrderItem::query()
            ->selectRaw('product_title, product_id, SUM(qty) as total_sold, SUM(subtotal) as total_revenue')
            ->groupBy('product_title', 'product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 5 aktivitas pesanan terbaru
        $recentOrders = Order::query()
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Chart data - Sales for last 30 days (daily)
        $dailyChart = $this->getDailyChartData();

        // Chart data - Sales for last 12 weeks (weekly)
        $weeklyChart = $this->getWeeklyChartData();

        // Chart data - Sales for last 12 months (monthly)
        $monthlyChart = $this->getMonthlyChartData();

        // Orders query with filters
        $ordersQuery = Order::query()->with(['user', 'items'])->latest();

        if ($status !== '') {
            $ordersQuery->where('status', $status);
        }

        if ($dateFrom !== '') {
            try {
                $ordersQuery->whereDate('created_at', '>=', Carbon::parse($dateFrom)->startOfDay());
            } catch (\Exception $e) {
                // Invalid date, ignore
            }
        }

        if ($dateTo !== '') {
            try {
                $ordersQuery->whereDate('created_at', '<=', Carbon::parse($dateTo)->endOfDay());
            } catch (\Exception $e) {
                // Invalid date, ignore
            }
        }

        if ($q !== '') {
            $ordersQuery->where(function ($sub) use ($q) {
                $sub->where('code', 'like', '%'.$q.'%')
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', '%'.$q.'%')
                            ->orWhere('email', 'like', '%'.$q.'%');
                    });
            });
        }

        $orders = $ordersQuery->paginate(15)->withQueryString();

        return view('admin.dashboard', [
            'stats' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'orders_pending' => $ordersPending,
                'orders_packed' => $ordersPacked,
                'orders_shipped' => $ordersShipped,
                'orders_done' => $ordersDone,
                'orders_canceled' => $ordersCanceled,
                'today_orders' => $todayOrders,
                'today_revenue' => $todayRevenue,
            ],
            'status_counts' => $statusCounts,
            'filters' => [
                'q' => $q,
                'status' => $status,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
            'orders' => $orders,
            'top_products' => $topProducts,
            'recent_orders' => $recentOrders,
            'chart_data' => [
                'daily' => $dailyChart,
                'weekly' => $weeklyChart,
                'monthly' => $monthlyChart,
            ],
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        if (!(Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')) {
            return redirect()->route('home');
        }

        $request->validate([
            'status' => ['required', 'string', 'in:Dikemas,Dikirim,Selesai,Dibatalkan'],
        ]);

        $previousStatus = $order->status;
        $newStatus = (string) $request->input('status');

        $order->update([
            'status' => $newStatus,
        ]);

        // Send email notification to customer
        try {
            $order->loadMissing(['user', 'items']);
            if ($order->user && $order->user->email) {
                Mail::to($order->user->email)->send(new OrderStatusUpdated($order, $previousStatus));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order status update email', [
                'order_code' => $order->code,
                'status' => $newStatus,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('admin_notice', 'Status pesanan berhasil diperbarui.');
    }

    public function invoice(Request $request, Order $order): Response|RedirectResponse
    {
        if (!(Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')) {
            return redirect()->route('home');
        }

        $order->loadMissing(['user', 'items']);

        $data = [
            'order' => $order,
            'company' => [
                'name' => 'Tempe Jaya Mandiri',
                'address' => 'Pliken, rt2 rw 5, Dusun IV, Pliken, Kec. Kembaran, Kabupaten Banyumas, Jawa Tengah 53182',
                'phone' => '085712149529',
                'email' => 'info@tempeflow.id',
            ],
        ];

        $pdf = Pdf::loadView('admin.invoice', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'invoice_' . $order->code . '.pdf';

        return $pdf->download($filename);
    }

    public function reportPdf(Request $request): Response|RedirectResponse
    {
        if (!(Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')) {
            return redirect()->route('home');
        }

        $dateFrom = $request->query('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->query('date_to', Carbon::now()->format('Y-m-d'));

        try {
            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate = Carbon::parse($dateTo)->endOfDay();
        } catch (\Exception $e) {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfDay();
        }

        // Get orders in date range
        $orders = Order::query()
            ->with(['user', 'items'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        // Calculate stats
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'Selesai')->count();
        $cancelledOrders = $orders->where('status', 'Dibatalkan')->count();
        $totalRevenue = (int) $orders->where('status', 'Selesai')->sum('grand_total');
        $pendingOrders = $orders->where('status', 'Menunggu Pembayaran')->count();

        // Top products
        $topProducts = OrderItem::query()
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'Selesai');
            })
            ->selectRaw('product_title, product_id, SUM(qty) as total_sold, SUM(subtotal) as total_revenue')
            ->groupBy('product_title', 'product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // Daily breakdown
        $dailyData = Order::query()
            ->where('status', 'Selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders_count, SUM(grand_total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $data = [
            'period' => [
                'from' => $startDate->format('d M Y'),
                'to' => $endDate->format('d M Y'),
            ],
            'stats' => [
                'total_orders' => $totalOrders,
                'completed_orders' => $completedOrders,
                'cancelled_orders' => $cancelledOrders,
                'pending_orders' => $pendingOrders,
                'total_revenue' => $totalRevenue,
            ],
            'orders' => $orders,
            'top_products' => $topProducts,
            'daily_data' => $dailyData,
            'company' => [
                'name' => 'Tempe Jaya Mandiri',
                'address' => 'Pliken, rt2 rw 5, Dusun IV, Pliken, Kec. Kembaran, Kabupaten Banyumas, Jawa Tengah 53182',
            ],
            'generated_at' => Carbon::now()->format('d M Y H:i'),
        ];

        $pdf = Pdf::loadView('admin.report-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'laporan_penjualan_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    public function export(Request $request): Response|RedirectResponse
    {
        if (!(Auth::user() && (string) (Auth::user()->role ?? '') === 'admin')) {
            return redirect()->route('home');
        }

        $status = trim((string) $request->query('status', ''));
        $dateFrom = $request->query('date_from', '');
        $dateTo = $request->query('date_to', '');

        $ordersQuery = Order::query()->with(['user', 'items'])->latest();

        if ($status !== '') {
            $ordersQuery->where('status', $status);
        }

        if ($dateFrom !== '') {
            try {
                $ordersQuery->whereDate('created_at', '>=', Carbon::parse($dateFrom)->startOfDay());
            } catch (\Exception $e) {
                // Invalid date, ignore
            }
        }

        if ($dateTo !== '') {
            try {
                $ordersQuery->whereDate('created_at', '<=', Carbon::parse($dateTo)->endOfDay());
            } catch (\Exception $e) {
                // Invalid date, ignore
            }
        }

        $orders = $ordersQuery->get();

        // Build CSV content
        $csvLines = [];
        $csvLines[] = implode(',', [
            'Kode Pesanan',
            'Tanggal',
            'Pelanggan',
            'Email',
            'Telepon',
            'Alamat',
            'Kota',
            'Kode Pos',
            'Status',
            'Jumlah Item',
            'Subtotal',
            'Grand Total',
            'Produk',
        ]);

        foreach ($orders as $o) {
            $itemsCount = (int) $o->items->sum('qty');
            $productNames = $o->items->pluck('product_title')->implode('; ');

            $csvLines[] = implode(',', [
                '"'.$o->code.'"',
                '"'.($o->created_at ? $o->created_at->format('Y-m-d H:i:s') : '').'"',
                '"'.str_replace('"', '""', $o->user?->name ?? '-').'"',
                '"'.($o->user?->email ?? '-').'"',
                '"'.($o->recipient_phone ?? '-').'"',
                '"'.str_replace('"', '""', $o->shipping_address ?? '-').'"',
                '"'.str_replace('"', '""', $o->shipping_city ?? '-').'"',
                '"'.($o->shipping_postal_code ?? '-').'"',
                '"'.$o->status.'"',
                $itemsCount,
                $o->subtotal,
                $o->grand_total,
                '"'.str_replace('"', '""', $productNames).'"',
            ]);
        }

        $csvContent = implode("\n", $csvLines);
        $filename = 'pesanan_'.date('Y-m-d_His').'.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function getDailyChartData(): array
    {
        $days = 30;
        $labels = [];
        $revenueData = [];
        $ordersData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');

            $dayRevenue = (int) Order::query()
                ->where('status', 'Selesai')
                ->whereDate('created_at', $date)
                ->sum('grand_total');

            $dayOrders = (int) Order::query()
                ->whereDate('created_at', $date)
                ->count();

            $revenueData[] = $dayRevenue;
            $ordersData[] = $dayOrders;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'orders' => $ordersData,
        ];
    }

    private function getWeeklyChartData(): array
    {
        $weeks = 12;
        $labels = [];
        $revenueData = [];
        $ordersData = [];

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekStart = Carbon::today()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::today()->subWeeks($i)->endOfWeek();

            $labels[] = 'W'.$weekStart->weekOfYear.' ('.$weekStart->format('d M').')';

            $weekRevenue = (int) Order::query()
                ->where('status', 'Selesai')
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->sum('grand_total');

            $weekOrders = (int) Order::query()
                ->whereBetween('created_at', [$weekStart, $weekEnd])
                ->count();

            $revenueData[] = $weekRevenue;
            $ordersData[] = $weekOrders;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'orders' => $ordersData,
        ];
    }

    private function getMonthlyChartData(): array
    {
        $months = 12;
        $labels = [];
        $revenueData = [];
        $ordersData = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthStart = Carbon::today()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::today()->subMonths($i)->endOfMonth();

            $labels[] = $monthStart->translatedFormat('M Y');

            $monthRevenue = (int) Order::query()
                ->where('status', 'Selesai')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('grand_total');

            $monthOrders = (int) Order::query()
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $revenueData[] = $monthRevenue;
            $ordersData[] = $monthOrders;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'orders' => $ordersData,
        ];
    }
}
