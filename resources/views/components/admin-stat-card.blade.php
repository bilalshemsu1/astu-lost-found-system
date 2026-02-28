@props([
    'label',
    'value' => 0,
    'valueClass' => 'text-gray-900',
])

<div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
    <p class="text-xs sm:text-sm text-gray-500">{{ $label }}</p>
    <p class="text-xl sm:text-2xl font-bold {{ $valueClass }} mt-1">{{ $value }}</p>
</div>

