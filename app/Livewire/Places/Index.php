<?php

namespace App\Livewire\Places;

use App\Models\Attraction;
use App\Models\Community;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('แหล่งท่องเที่ยวและแผนที่')]
class Index extends Component
{
    #[Url] public string $q = '';
    #[Url] public string $type = '';
    #[Url] public string $faith = '';
    #[Url] public string $community = '';

    /** ตำแหน่งผู้ใช้ (ได้จาก geolocation ของเบราว์เซอร์) */
    public ?float $userLat = null;
    public ?float $userLng = null;

    public function updated(): void
    {
        $this->pushMarkers();
    }

    public function setLocation(float $lat, float $lng): void
    {
        // รับเฉพาะพิกัดที่อยู่ในช่วงที่ถูกต้อง
        if (abs($lat) > 90 || abs($lng) > 180) {
            return;
        }
        $this->userLat = $lat;
        $this->userLng = $lng;
        $this->pushMarkers();
    }

    public function clearLocation(): void
    {
        $this->reset('userLat', 'userLng');
    }

    public function resetFilters(): void
    {
        $this->reset('q', 'type', 'faith', 'community');
        $this->pushMarkers();
    }

    private function query(): Builder
    {
        $query = Attraction::active()->with('community')
            ->when($this->q, fn ($q) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$this->q}%")
                ->orWhere('summary', 'like', "%{$this->q}%")))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->when($this->faith, fn ($q) => $q->where('faith', $this->faith))
            ->when($this->community, fn ($q) => $q->where('community_id', $this->community));

        return $this->userLat !== null
            ? $query->nearest($this->userLat, $this->userLng)
            : $query->orderByDesc('is_featured')->orderBy('name');
    }

    private function markers(Collection $places): array
    {
        return $places->map(fn (Attraction $a) => $a->toMarker() + [
            'distance_km' => $a->distance_km ?? null,
        ])->values()->all();
    }

    private function pushMarkers(): void
    {
        $this->dispatch('markers-updated', markers: $this->markers($this->query()->get()));
    }

    public function render()
    {
        $places = $this->query()->get();

        return view('livewire.places.index', [
            'places' => $places,
            'markers' => $this->markers($places),
            'communities' => Community::active()->orderBy('name')->get(),
            'types' => Attraction::TYPES,
            'faiths' => Attraction::FAITHS,
        ]);
    }
}
