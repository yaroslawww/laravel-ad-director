<?php

namespace AdDirector\GPT;

use Illuminate\Support\Str;

/**
 * @link https://developers.google.com/publisher-tag/reference#googletag.defineSlot
 */
class Slot
{
    protected string $divId;

    protected array $targets = [];

    public function __construct(
        protected string      $adUnitPath,
        protected Size|string $size,
        ?string               $divId = null
    ) {
        if (is_null($divId)) {
            $divId = 'id_'.Str::random(8).'_'.time();
        }
        $this->divId = $divId;
    }

    public static function make(...$arguments): static
    {
        return new static(...$arguments);
    }

    public function targets(): array
    {
        return $this->targets;
    }

    public function addTarget(string $key, string|array $value): static
    {
        $this->targets[$key] = $value;

        return $this;
    }

    public function clearTargets(string|array|null $key = null): static
    {
        if (is_null($key)) {
            $this->targets[] = [];

            return $this;
        }

        foreach (((array) $key) as $key) {
            unset($this->targets[$key]);
        }

        return $this;
    }

    public function adUnitPath(): string
    {
        return $this->adUnitPath;
    }

    public function divId(): string
    {
        return $this->divId;
    }

    public function size(): Size|string
    {
        return $this->size;
    }
}
