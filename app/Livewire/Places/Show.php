<?php

namespace App\Livewire\Places;

use App\Models\Attraction;
use App\Models\Product;
use Livewire\Component;

class Show extends Component
{
    public Attraction $place;

    public function mount(Attraction $place): void
    {
        abort_unless($place->is_active, 404);
        $this->place = $place->load('community');

        // นับยอดเข้าชม 1 ครั้งต่อ session
        $key = "viewed_place_{$place->id}";
        if (! session()->has($key)) {
            $place->increment('views');
            session()->put($key, true);
        }
    }

    public function render()
    {
        $nearby = ($this->place->lat && $this->place->lng)
            ? Attraction::active()->with('community')->whereKeyNot($this->place->id)
                ->nearest($this->place->lat, $this->place->lng)->take(4)->get()
            : collect();

        return view('livewire.places.show', [
            'nearby' => $nearby,
            'products' => Product::active()->with('community')->where('community_id', $this->place->community_id)->take(4)->get(),
            'markers' => collect([$this->place])->concat($nearby)
                ->map(fn ($a) => $a->toMarker() + ['distance_km' => $a->distance_km ?? null])->all(),
        ])->title($this->place->name);
    }
}
