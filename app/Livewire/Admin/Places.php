<?php

namespace App\Livewire\Admin;

use App\Models\Attraction;
use App\Models\Community;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('แหล่งท่องเที่ยว')]
class Places extends Component
{
    use WithFileUploads, WithPagination;

    private const FIELDS = [
        'community_id', 'name', 'type', 'faith', 'summary', 'description', 'address', 'lat', 'lng',
        'opening_hours', 'entry_fee', 'best_time', 'contact_phone', 'eco_tips', 'is_featured', 'is_active',
    ];

    #[Url] public string $search = '';
    #[Url] public string $community = '';

    public bool $showForm = false;
    public ?int $editingId = null;
    public array $form = [];
    public $image;
    public array $galleryUploads = [];
    public array $gallery = [];

    protected function rules(): array
    {
        return [
            'form.community_id' => 'required|exists:communities,id',
            'form.name' => 'required|string|max:200',
            'form.type' => ['required', Rule::in(array_keys(Attraction::TYPES))],
            'form.faith' => ['nullable', Rule::in(array_keys(Attraction::FAITHS))],
            'form.summary' => 'nullable|string|max:300',
            'form.description' => 'nullable|string',
            'form.address' => 'nullable|string|max:255',
            'form.lat' => 'nullable|numeric|between:-90,90|required_with:form.lng',
            'form.lng' => 'nullable|numeric|between:-180,180|required_with:form.lat',
            'form.opening_hours' => 'nullable|string|max:150',
            'form.entry_fee' => 'nullable|string|max:100',
            'form.best_time' => 'nullable|string|max:150',
            'form.contact_phone' => 'nullable|string|max:20',
            'form.eco_tips' => 'nullable|string',
            'form.is_featured' => 'boolean',
            'form.is_active' => 'boolean',
            'image' => 'nullable|image|max:4096',
            'galleryUploads.*' => 'image|max:4096',
        ];
    }

    protected array $validationAttributes = [
        'form.name' => 'ชื่อสถานที่', 'form.community_id' => 'ชุมชน', 'form.lat' => 'ละติจูด', 'form.lng' => 'ลองจิจูด',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->reset('editingId', 'image', 'galleryUploads', 'gallery');
        $this->form = array_fill_keys(self::FIELDS, null);
        $this->form['community_id'] = $this->community ?: Community::value('id');
        $this->form['type'] = 'culture';
        $this->form['is_featured'] = false;
        $this->form['is_active'] = true;
        $this->showForm = true;
    }

    public function edit(Attraction $place): void
    {
        $this->resetValidation();
        $this->reset('image', 'galleryUploads');
        $this->editingId = $place->id;
        $this->form = $place->only(self::FIELDS);
        $this->gallery = $place->gallery ?? [];
        $this->showForm = true;
    }

    public function removeGalleryImage(int $i): void
    {
        unset($this->gallery[$i]);
        $this->gallery = array_values($this->gallery);
    }

    public function save(): void
    {
        $this->validate();

        $data = array_map(fn ($v) => $v === '' ? null : $v, $this->form);
        $data['is_featured'] = (bool) $data['is_featured'];
        $data['is_active'] = (bool) $data['is_active'];

        if ($this->image) {
            $data['image'] = $this->image->store('places', 'public');
        }
        foreach ($this->galleryUploads as $file) {
            $this->gallery[] = $file->store('places/gallery', 'public');
        }
        $data['gallery'] = array_values($this->gallery);

        if ($this->editingId) {
            Attraction::findOrFail($this->editingId)->update($data);
        } else {
            $data['slug'] = (Str::slug($data['name']) ?: 'place').'-'.Str::lower(Str::random(5));
            Attraction::create($data);
        }

        $this->showForm = false;
        $this->dispatch('toast', message: 'บันทึกสถานที่แล้ว');
    }

    public function toggle(Attraction $place, string $field): void
    {
        abort_unless(in_array($field, ['is_active', 'is_featured']), 422);
        $place->update([$field => ! $place->{$field}]);
    }

    public function delete(Attraction $place): void
    {
        collect([$place->image, ...($place->gallery ?? [])])->filter()
            ->each(fn ($path) => Storage::disk('public')->delete($path));
        $place->delete();
        $this->dispatch('toast', message: 'ลบสถานที่แล้ว');
    }

    public function render()
    {
        return view('livewire.admin.places', [
            'places' => Attraction::with('community')
                ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->when($this->community, fn ($q) => $q->where('community_id', $this->community))
                ->latest()->paginate(15),
            'communities' => Community::orderBy('name')->get(),
            'types' => Attraction::TYPES,
            'faiths' => Attraction::FAITHS,
        ]);
    }
}
