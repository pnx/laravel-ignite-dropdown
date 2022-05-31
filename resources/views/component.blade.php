@php
if ($errors->has($name)) {
    $border = 'border-red-400 focus:border-red-500 focus:ring-red-500';
} else {
    $border = 'border-gray-200 focus:border-gray-300 focus:ring-gray-300';
}
@endphp

<div x-data="{ open: @entangle('menu_open').defer }"
    x-on:keydown.escape.prevent.stop="open && $wire.close()"
    class="relative inline-block w-full">

    {{-- Hidden input with the actual value --}}
    <input type="hidden" name="{!! $name !!}" value="{{ $this->value }}" />

    {{-- Visual input field --}}
    <div class="flex items-center cursor-pointer"
        x-on:click="!open && $wire.open()"
        x-on:click.away="open && $wire.close()">

        <div class="bg-white px-3 py-2 w-full h-10 text-gray-600 rounded-md border {!! $border !!} focus:ring-opacity-50">
            {{ $this->renderSelection() }}
            <input type="text" wire:model="search" :name="$name . '_search'" class="absolute w-11/12 bg-transparent select-none" autocomplete="off" />
        </div>

        @if ($this->getIcon())
        <x-dynamic-component component="icon" :name="$this->getIcon()" class="-ml-6 h-5 w-5 text-gray-500" />
        @endif
    </div>

    {{-- Dropdown menu --}}
    @if ($menu_open)
    <ul x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute block w-full mt-1 z-50 rounded border bg-white divide-y overflow-y-auto max-h-96">

        @if (!$required)
        <li wire:click="select()" class="px-4 py-2 text-center text-gray-500 cursor-pointer hover:bg-primary hover:text-white">{{ __('None') }}</li>
        @endif

        @if (count($options) < 1)
        <li class="px-4 py-2 text-center text-gray-500">{{ __($no_results) }}</li>
        @else
        @foreach($options as $option)
        <li wire:key="{{ $this->value($option) }}" wire:click="select('{{ $this->value($option) }}')" class="px-4 py-2 cursor-pointer first:rounded-t last:rounded-b hover:bg-primary hover:text-white">
            {{ $this->renderOption($option) }}
        </li>
        @endforeach
        @endif
    </ul>
    @endif

    @error($name)
    <p class="text-red-400 text-sm">{{ $message }}</p>
    @enderror

</div>
