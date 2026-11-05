<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('คำสั่งซื้อ')]
class Orders extends Component
{
    use WithPagination;

    #[Url] public string $status = '';
    public string $search = '';
    public ?int $viewingId = null;
    public string $tracking_no = '';

    public function view(int $id): void
    {
        $this->viewingId = $id;
        $this->tracking_no = (string) Order::whereKey($id)->value('tracking_no');
    }

    public function setStatus(Order $order, string $status): void
    {
        abort_unless(array_key_exists($status, Order::STATUSES), 422);

        DB::transaction(function () use ($order, $status) {
            // ยกเลิกออเดอร์ => คืนสต็อก (ทำครั้งเดียว)
            if ($status === 'cancelled' && $order->status !== 'cancelled') {
                foreach ($order->items()->with('product')->get() as $item) {
                    $item->product?->increment('stock', $item->qty);
                }
            }
            $order->update(['status' => $status]);
        });

        $this->dispatch('toast', message: "อัปเดต {$order->code} แล้ว");
    }

    public function saveTracking(): void
    {
        $this->validate(['tracking_no' => 'required|string|max:50']);
        Order::findOrFail($this->viewingId)->update(['tracking_no' => $this->tracking_no, 'status' => 'shipped']);
        $this->dispatch('toast', message: 'บันทึกเลขพัสดุแล้ว');
    }

    public function render()
    {
        return view('livewire.admin.orders', [
            'orders' => Order::with('user')->withCount('items')
                ->when($this->status, fn ($q) => $q->where('status', $this->status))
                ->when($this->search, fn ($q) => $q->where(fn ($w) => $w
                    ->where('code', 'like', "%{$this->search}%")
                    ->orWhere('ship_name', 'like', "%{$this->search}%")))
                ->latest()->paginate(20),
            'viewing' => $this->viewingId ? Order::with('items', 'user')->find($this->viewingId) : null,
        ]);
    }
}
