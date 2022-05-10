@if ($thumbnail)
<div class="flex space-x-2 items-center">
    <div class="w-6 h-6">{!! $thumbnail !!}</div>
    <div>{{ $title }}</div>
</div>
@else
{{ $title }}
@endif
