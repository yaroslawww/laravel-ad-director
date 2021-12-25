<?php /**
 * @var string $variableName
 * @var array $map
 */ ?>
var {{$variableName}} = googletag.sizeMapping();
@foreach ($map as $mapItem)
    <?php
    $viewPortData = json_encode($mapItem[0]);
    $sizeValueData = json_encode($mapItem[1]);
    ?>
    {{$variableName}}.addSize({{$viewPortData}}, {{$sizeValueData}});
@endforeach
{{$variableName}}.build();
