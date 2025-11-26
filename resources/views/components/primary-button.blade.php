@props(['type' => 'submit'])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'bg-[#19264bff] hover:bg-[#2a3a6b] text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200']) }}>
    {{ $slot }}
</button>
