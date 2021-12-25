<?php

namespace AdDirector\GPT;

class SizeMap
{
    protected array $map;

    public function __construct(array ...$items)
    {
        $this->map = $items;
    }

    public function addSize(array $viewportSize, array|string $slotSize): static
    {
        $this->map[] = [
            $viewportSize,
            $slotSize,
        ];

        return $this;
    }

    public function map(): array
    {
        return $this->map;
    }
}
