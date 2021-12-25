<?php

namespace AdDirector\GPT;

use Illuminate\Support\Facades\View;

class Presenter
{
    public bool $importVendorScripts = true;

    public static string $jsMapPrefix = 'gptmap_';

    public static string $jsSlotVariableName = 'gptSlot';

    protected array $locationHtml = [];

    protected string $scriptSizeMaps = '';

    protected string $scriptSlots = '';

    protected string $scriptDisplayAds = '';

    public function __construct(AdGPT $adManager)
    {
        $this->importVendorScripts = $adManager->isImportVendorScripts();

        /**
         * @var string $sizeName
         * @var \AdDirector\GPT\Size $size
         */
        foreach ($adManager::getSizes() as $sizeName => $size) {
            if ($map = $size->map()) {
                $this->addSizeMapToScript($sizeName, $map);
            }
        }

        /**
         * @var string $locationName
         * @var \AdDirector\GPT\Slot $slot
         */
        foreach ($adManager->locations() as $locationName => $slot) {
            $this->locationHtml[$locationName] = View::make('ad-director::gpt.location-html', [
                'locationName' => $locationName,
                'locationId'   => $slot->divId(),
            ])->render();

            if (($size = $slot->size()) instanceof Size
                && ($map = $size->map())) {
                $this->addSizeMapToScript($slot->divId(), $map);
            }

            $this->addSlotToScript($slot);
            $this->addDisplayToScript($slot);
        }
    }

    public function adLocation(string $name): string
    {
        return $this->locationHtml[$name] ?? '';
    }

    protected function addSizeMapToScript(string $mapName, SizeMap $map): static
    {
        $prefix = static::$jsMapPrefix;
        $this->scriptSizeMaps .= View::make('ad-director::gpt.define-size-map', [
            'variableName' => "{$prefix}{$mapName}",
            'map'          => $map->map(),
        ])->render();

        return $this;
    }

    protected function addSlotToScript(Slot $slot): static
    {
        $sizeName = $slot->divId();
        $size     = $slot->size();
        if (is_string($size)) {
            $sizeName = $size;
            $size     = AdGPT::getSize($sizeName);
        }
        if (!$size) {
            return $this;
        }

        $this->scriptSlots .= View::make('ad-director::gpt.define-slot', [
            'jsSlotVariableName' => static::$jsSlotVariableName,
            'mapVariableName'    => $size->map()?(static::$jsMapPrefix.$sizeName):null,
            'adUnitPath'         => $slot->adUnitPath(),
            'size'               => json_encode($size->size()),
            'divId'              => $slot->divId(),
            'targets'            => $slot->targets(),
        ])->render();

        return $this;
    }

    protected function addDisplayToScript(Slot $slot): static
    {
        // language=JavaScript
        $script = "googletag.display('{$slot->divId()}');".PHP_EOL;

        $this->scriptDisplayAds .= $script;

        return $this;
    }

    public function defineSizesMapsScript(): string
    {
        return $this->scriptSizeMaps;
    }

    public function defineSlotsScript(): string
    {
        return $this->scriptSlots;
    }

    public function displayAdsScript(): string
    {
        return $this->scriptDisplayAds;
    }
}
