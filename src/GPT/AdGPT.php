<?php

namespace AdDirector\GPT;

use AdDirector\Core\AdManager;
use AdDirector\Core\HasVendorScripts;
use AdDirector\RenderMinifier;
use Illuminate\Support\Facades\View;

class AdGPT extends AdManager
{
    use HasVendorScripts, HasGlobalSizes;

    protected array $locations = [];

    protected ?Presenter $presenter = null;

    public function configurationScript(bool $minify = true): string
    {
        $view = View::make('ad-director::gpt.configuration-scripts', [
            'adPresenter' => $this->presenter(),
        ])->render();

        return $minify ? RenderMinifier::minify($view) : $view;
    }

    public function displayScript(bool $minify = true): string
    {
        $view = View::make('ad-director::gpt.display-scripts', [
            'adPresenter' => $this->presenter(),
        ])->render();

        return $minify ? RenderMinifier::minify($view) : $view;
    }

    public function adLocation(string $name, bool $minify = true): string
    {
        $view = $this->presenter()->adLocation($name);

        return $minify ? RenderMinifier::minify($view) : $view;
    }

    protected function presenter(bool $rebuild = false): Presenter
    {
        if ($rebuild || !$this->presenter) {
            $this->presenter = new Presenter($this);
        }

        return $this->presenter;
    }

    /**
     * @param  \AdDirector\GPT\Slot  $slot
     * @param  string|null  $name  If name is null than ad unit will be used.
     *
     * @return static
     */
    public function addLocation(Slot $slot, ?string $name = null): static
    {
        if (is_null($name)) {
            $name = $slot->adUnitPath();
        }

        $this->locations[$name] = $slot;

        return $this;
    }

    public function locations(): array
    {
        return $this->locations;
    }
}
