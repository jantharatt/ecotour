<?php

namespace App\Livewire\Communities;

use App\Models\Community;
use Livewire\Component;

class Show extends Component
{
    public Community $community;

    public function mount(Community $community): void
    {
        abort_unless($community->is_active, 404);
        $this->community = $community->load([
            'attractions' => fn ($q) => $q->active()->orderByDesc('is_featured'),
            'products' => fn ($q) => $q->active()->with('community'),
        ]);
    }

    public function render()
    {
        $markers = $this->community->attractions
            ->each->setRelation('community', $this->community)
            ->map->toMarker()->values()->all();

        return view('livewire.communities.show', ['markers' => $markers])
            ->title($this->community->name);
    }
}
