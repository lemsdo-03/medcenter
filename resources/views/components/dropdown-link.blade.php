@props(['class' => ''])

<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 focus:outline-none focus:bg-gradient-to-r focus:from-cyan-50 focus:to-blue-50 transition duration-150 ease-in-out rounded-lg ' . $class]) }}>
    {{ $slot }}
</a>
