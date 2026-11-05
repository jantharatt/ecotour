<?php

namespace App\Livewire\Communities;

use App\Models\Community;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('ชุมชนของเรา')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.communities.index', [
            'communities' => Community::active()
                ->withCount([
                    'attractions' => fn ($q) => $q->active(),
                    'products' => fn ($q) => $q->active(),
                ])->orderBy('name')->get(),
        ]);
    }
}
