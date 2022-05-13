@if ($resource->thumbnail())
<div class="flex space-x-2 items-center">
    <div class="w-6 h-6">{!! $resource->thumbnail() !!}</div>
    <div>{{ $resource->title() }}</div>
</div>
@else
{{ $resource->title() }}
@endif
