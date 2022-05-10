@if ($thumbnail)
<div class="flex space-x-2 items-center">
    <div class="w-8 h-8">{!! $thumbnail !!}</div>
    @if ($subtitle)
    <div>
        <div>{{ $title }}</div>
        @include('ignite-dropdown::model.subtitle')
    </div>
    @else
    <div>{{ $title }}</div>
    @endif
</div>
@elseif ($subtitle)
<div>{{ $title }}</div>
@include('ignite-dropdown::model.subtitle')
@else
{{ $title }}
@endif
