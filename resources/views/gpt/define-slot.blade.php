<?php /**
 * @var string $jsSlotVariableName
 * @var string $mapVariableName
 * @var string $adUnitPath
 * @var string $size
 * @var string $divId
 * @var array $targets
 */ ?>
{{$jsSlotVariableName}} = googletag.defineSlot('{{$adUnitPath}}', {!! $size !!}, '{{$divId}}');
@foreach ($targets as $targetKey => $targetValue)<?php $targetValue = json_encode($targetValue); ?>
{{$jsSlotVariableName}}.setTargeting('{{$targetKey}}', {!! $targetValue !!});
@endforeach
@if(!empty($mapVariableName))
    {{$jsSlotVariableName}}.defineSizeMapping({{$mapVariableName}});
@endif
{{$jsSlotVariableName}}.addService(adsGptService);
