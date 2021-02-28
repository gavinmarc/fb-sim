@props(['active'])

@php
$classes = ($active ?? false)
  ? 'block pl-3 pr-4 py-2 border-l-4 border-yellow-400 text-base font-medium transition duration-150 ease-in-out'
  : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium hover:text-yellow-400 hover:border-gray-100 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>
