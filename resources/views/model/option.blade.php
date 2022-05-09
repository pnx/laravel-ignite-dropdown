@if ($subtitle)
<div>{{ $title }}</div>
<div class="text-gray text-sm">{{ $subtitle }}</div>
@else
{{ $title }}
@endif
