<?php

namespace App\Livewire;

use App\Models\Attraction;
use App\Models\Community;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('3 สายน้ำ 3 ศาสนา · ท่องเที่ยวชุมชนอยุธยา')]
class Home extends Component
{
    public function render()
    {
        $places = Attraction::active()->with('community')->get();

        return view('livewire.home', [
            'faithPlaces' => $places->whereNotNull('faith')
                ->sortBy(fn ($a) => array_search($a->faith, array_keys(Attraction::FAITHS)))
                ->unique('faith')->values(),
            'featured' => $places->where('is_featured', true)->take(6),
            'markers' => $places->map->toMarker()->values()->all(),
            'communities' => Community::active()->withCount(['attractions', 'products'])->get(),
            'products' => Product::active()->with('community')->latest()->take(8)->get(),
        ]);
    }
}
