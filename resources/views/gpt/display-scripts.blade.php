<?php /** @var \AdDirector\GPT\Presenter $adPresenter */
// language=JavaScript
?>
/* AdDirector:GPT:display */
function displayGPTAd() {
window.googletag = window.googletag || {cmd: []};
    googletag.cmd.push(function () {
        {!! $adPresenter->displayAdsScript() !!}
    });
}
displayGPTAd();
