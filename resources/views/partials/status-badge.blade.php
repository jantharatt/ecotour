@php
    $map = [
        'pending' => 'badge-sun', 'paid' => 'badge-lagoon', 'confirmed' => 'badge-leaf',
        'packing' => 'badge-lagoon', 'shipped' => 'badge-leaf', 'completed' => 'badge-gray', 'cancelled' => 'badge-red',
    ];
@endphp
<span class="{{ $map[$status] ?? 'badge-gray' }}">{{ $label }}</span>
