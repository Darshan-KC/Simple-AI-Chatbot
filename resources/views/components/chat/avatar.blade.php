@props([
    'initials' => '??',
    'size' => 'md',
    'online' => false,
])

@php
    $sizes = [
        'sm' => 'h-8 w-8 text-[11px]',
        'md' => 'h-10 w-10 text-xs',
        'lg' => 'h-12 w-12 text-sm',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div>
    <div class="relative shrink-0">
        <div
            {{ $attributes->merge([
                'class' => "{$sizeClass} flex items-center justify-center rounded-full bg-gray-200 font-semibold text-gray-600",
            ]) }}>
            {{ $initials }}
        </div>

        @if ($online)
            <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-white bg-green-500"></span>
        @endif
    </div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div>
