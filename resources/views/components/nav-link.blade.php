@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 bg-gray-100 text-gray-900 rounded-xl text-sm font-semibold leading-5 shadow-sm border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-200 ease-in-out'
            : 'inline-flex items-center px-4 py-2 text-gray-700 hover:text-blue-700 hover:bg-gray-50 rounded-xl text-sm font-medium leading-5 focus:outline-none focus:text-blue-700 focus:bg-gray-50 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
