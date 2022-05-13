@if ($resource->thumbnail())
<div class="flex space-x-2 items-center">
    <div class="w-8 h-8">{!! $resource->thumbnail() !!}</div>
    @if ($resource->subtitle())
    <div>
        <div>{{ $resource->title() }}</div>
        @include('ignite-dropdown::resource.subtitle')
    </div>
    @else
    <div>{{ $resource->title() }}</div>
    @endif
</div>
@elseif ($resource->subtitle())
<div>{{ $resource->title()}}</div>
@include('ignite-dropdown::resource.subtitle')
@else
{{ $resource->title() }}
@endif
