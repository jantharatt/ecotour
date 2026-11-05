<?php

namespace App\Livewire\Admin;

use App\Models\Community;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
#[Title('ชุมชน')]
class Communities extends Component
{
    use WithFileUploads;

    private const FIELDS = [
        'name', 'tagline', 'description', 'conservation', 'address', 'subdistrict', 'district', 'province',
        'lat', 'lng', 'contact_name', 'contact_phone', 'facebook_url', 'line_id', 'is_active',
    ];

    public bool $showForm = false;
    public ?int $editingId = null;
    public array $form = [];
    public $cover;

    public function create(): void
    {
        $this->resetValidation();
        $this->reset('editingId', 'cover');
        $this->form = array_fill_keys(self::FIELDS, null);
        $this->form['province'] = 'พระนครศรีอยุธยา';
        $this->form['is_active'] = true;
        $this->showForm = true;
    }

    public function edit(Community $community): void
    {
        $this->resetValidation();
        $this->reset('cover');
        $this->editingId = $community->id;
        $this->form = $community->only(self::FIELDS);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'form.name' => 'required|string|max:200',
            'form.tagline' => 'nullable|string|max:200',
            'form.description' => 'nullable|string',
            'form.conservation' => 'nullable|string',
            'form.address' => 'nullable|string|max:255',
            'form.subdistrict' => 'nullable|string|max:100',
            'form.district' => 'nullable|string|max:100',
            'form.province' => 'nullable|string|max:100',
            'form.lat' => 'nullable|numeric|between:-90,90',
            'form.lng' => 'nullable|numeric|between:-180,180',
            'form.contact_name' => 'nullable|string|max:100',
            'form.contact_phone' => 'nullable|string|max:20',
            'form.facebook_url' => 'nullable|url|max:255',
            'form.line_id' => 'nullable|string|max:50',
            'cover' => 'nullable|image|max:4096',
        ], [], ['form.name' => 'ชื่อชุมชน']);

        $data = array_map(fn ($v) => $v === '' ? null : $v, $this->form);
        $data['is_active'] = (bool) ($this->form['is_active'] ?? true);
        if ($this->cover) {
            $data['cover_image'] = $this->cover->store('communities', 'public');
        }

        if ($this->editingId) {
            Community::findOrFail($this->editingId)->update($data);
        } else {
            $data['slug'] = (Str::slug($data['name']) ?: 'community').'-'.Str::lower(Str::random(5));
            Community::create($data);
        }
        $this->showForm = false;
        $this->dispatch('toast', message: 'บันทึกชุมชนแล้ว');
    }

    public function render()
    {
        return view('livewire.admin.communities', [
            'communities' => Community::withCount('attractions', 'products')->orderBy('name')->get(),
        ]);
    }
}
