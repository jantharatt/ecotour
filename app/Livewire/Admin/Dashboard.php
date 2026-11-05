<?php

namespace App\Livewire\Admin;

use App\Models\Attraction;
use App\Models\Community;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('แดชบอร์ด')]
class Dashboard extends Component
{
    public function render()
    {
        $paid = ['paid', 'packing', 'shipped', 'completed'];

        // ยอดขาย 6 เดือนล่าสุด
        $months = collect(range(5, 0))->map(function ($i) use ($paid) {
            $m = now()->subMonths($i);

            return [
                'label' => $m->locale('th')->translatedFormat('M'),
                'total' => (float) Order::whereIn('status', $paid)
                    ->whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->sum('total'),
            ];
        });

        return view('livewire.admin.dashboard', [
            'stats' => [
                'sales_month' => Order::whereIn('status', $paid)->where('created_at', '>=', now()->startOfMonth())->sum('total'),
                'orders_waiting' => Order::where('status', 'paid')->count(),
                'orders_to_ship' => Order::where('status', 'packing')->count(),
                'places' => Attraction::active()->count(),
                'communities' => Community::active()->count(),
            ],
            'months' => $months,
            'lowStock' => Product::active()->where('stock', '<', 10)->orderBy('stock')->take(5)->get(),
            'topProducts' => OrderItem::selectRaw('product_name, SUM(qty) as qty, SUM(line_total) as revenue')
                ->whereHas('order', fn ($q) => $q->whereIn('status', $paid))
                ->groupBy('product_name')->orderByDesc('qty')->take(5)->get(),
            'topPlaces' => Attraction::active()->orderByDesc('views')->take(5)->get(),
            'missingLocation' => Attraction::active()->where(fn ($q) => $q->whereNull('lat')->orWhereNull('lng'))->count(),
        ]);
    }
}
