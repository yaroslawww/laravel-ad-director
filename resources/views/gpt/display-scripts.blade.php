<?php /** @var \AdDirector\GPT\Presenter $adPresenter */ ?>
<!--AdDirector:GPT:display -->
<script>
    window.googletag = window.googletag || {cmd: []};
    googletag.cmd.push(function () {
        {!! $adPresenter->displayAdsScript() !!}
    });
</script>
