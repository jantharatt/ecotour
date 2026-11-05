<?php

namespace App\Livewire\Account;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrderShow extends Component
{
    use WithFileUploads;

    public Order $order;
    public $slip;

    public function mount(Order $order): void
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $this->order = $order->load('items.product');
    }

    public function uploadSlip(): void
    {
        $this->validate(['slip' => 'required|image|max:4096']);
        abort_unless($this->order->status === 'pending', 422);

        $this->order->update([
            'payment_slip' => $this->slip->store('slips', 'public'),
            'status' => 'paid',
        ]);
        $this->reset('slip');
        session()->flash('success', 'แนบสลิปเรียบร้อย รอเจ้าหน้าที่ตรวจสอบ');
    }

    public function cancel(): void
    {
        abort_unless($this->order->status === 'pending', 422);
        // คืนสต็อก
        foreach ($this->order->items as $item) {
            $item->product?->increment('stock', $item->qty);
        }
        $this->order->update(['status' => 'cancelled']);
    }

    public function render()
    {
        return view('livewire.account.order-show')->title('คำสั่งซื้อ '.$this->order->code);
    }
}
