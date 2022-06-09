@if ($description)
<div>{{ __($name) }}</div>
<span class="text-gray-500 text-sm">{{ $description }}</span>
@else
{{ __($name) }}
@endif
