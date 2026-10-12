@props([
    'label',
])

<div>
    <!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
    <div class="my-6 flex items-center gap-3">
    <div class="h-px flex-1 bg-gray-200"></div>

    <span class="rounded-full bg-gray-200 px-3 py-1 text-[11px] font-medium text-gray-500">
        {{ $label }}
    </span>

    <div class="h-px flex-1 bg-gray-200"></div>
</div>
</div>