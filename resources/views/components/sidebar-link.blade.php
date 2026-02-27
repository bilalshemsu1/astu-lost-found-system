@props(['href', 'label', 'active'])

<a href="{{ $href }}" {{ $attributes->class([
    'flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors',
    'sidebar-link active' => $active,
    'sidebar-link' => !$active
]) }}>
    @if(isset($icon))
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {{ $icon }}
        </svg>
    @endif

    {{ $label }}

    {{ $slot }}
</a>