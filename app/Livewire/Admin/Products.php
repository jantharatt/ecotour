<?php

namespace App\Livewire\Admin;

use App\Models\Community;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('จัดการสินค้า')]
class Products extends Component
{
    use WithFileUploads, WithPagination;

    private const FIELDS = [
        'name', 'community_id', 'product_category_id', 'producer', 'story', 'description',
        'price', 'stock', 'unit', 'weight_gram', 'is_otop', 'is_active',
    ];

    public string $search = '';
    public bool $showForm = false;
    public ?int $editingId = null;

    public array $form = [];
    public $image;

    protected function rules(): array
    {
        return [
            'form.name' => 'required|string|max:200',
            'form.community_id' => 'required|exists:communities,id',
            'form.product_category_id' => 'required|exists:product_categories,id',
            'form.producer' => 'nullable|string|max:200',
            'form.story' => 'nullable|string',
            'form.description' => 'nullable|string',
            'form.price' => 'required|numeric|min:0',
            'form.stock' => 'required|integer|min:0',
            'form.unit' => 'required|string|max:30',
            'form.weight_gram' => 'required|integer|min:0',
            'form.is_otop' => 'boolean',
            'form.is_active' => 'boolean',
            'image' => 'nullable|image|max:4096',
        ];
    }

    protected array $validationAttributes = [
        'form.name' => 'ชื่อสินค้า', 'form.price' => 'ราคา', 'form.stock' => 'สต็อก',
        'form.community_id' => 'ชุมชน', 'form.product_category_id' => 'หมวดหมู่',
    ];

    public function create(): void
    {
        $this->resetValidation();
        $this->reset('editingId', 'image');
        $this->form = [
            'name' => '', 'community_id' => Community::value('id'), 'product_category_id' => ProductCategory::value('id'),
            'producer' => '', 'story' => '', 'description' => '', 'price' => '', 'stock' => 0,
            'unit' => 'ชิ้น', 'weight_gram' => 0, 'is_otop' => false, 'is_active' => true,
        ];
        $this->showForm = true;
    }

    public function edit(Product $product): void
    {
        $this->resetValidation();
        $this->reset('image');
        $this->editingId = $product->id;
        $this->form = $product->only(self::FIELDS);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        $data = $this->form;

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        if ($this->editingId) {
            Product::findOrFail($this->editingId)->update($data);
        } else {
            $data['slug'] = Str::slug($data['name']) ?: 'product';
            $data['slug'] .= '-'.Str::lower(Str::random(5));
            Product::create($data);
        }

        $this->showForm = false;
        $this->dispatch('toast', message: 'บันทึกสินค้าแล้ว');
    }

    public function toggleActive(Product $product): void
    {
        $product->update(['is_active' => ! $product->is_active]);
    }

    public function delete(Product $product): void
    {
        $product->delete();
        $this->dispatch('toast', message: 'ลบสินค้าแล้ว');
    }

    public function render()
    {
        return view('livewire.admin.products', [
            'products' => Product::with('category', 'community')
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->latest()->paginate(15),
            'categories' => ProductCategory::all(),
            'communities' => Community::orderBy('name')->get(),
        ]);
    }
}
