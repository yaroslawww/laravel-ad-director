<?php /** @var \AdDirector\GPT\Presenter $adPresenter */ ?>
<!--AdDirector:GPT:configuration -->
@if($adPresenter->importVendorScripts)
    @include('ad-director::gpt.vendor-scripts')
@endif
<script>
    window.googletag = window.googletag || {cmd: []};
    googletag.cmd.push(function () {
        var adsGptService = googletag.pubads();
        var {{$adPresenter::$jsSlotVariableName}};
        {!! $adPresenter->defineSizesMapsScript() !!}
        {!! $adPresenter->defineSlotsScript() !!}
        adsGptService.enableSingleRequest();
        googletag.enableServices();
    });
</script>
