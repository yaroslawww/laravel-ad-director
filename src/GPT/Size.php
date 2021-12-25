<?php

namespace AdDirector\GPT;

class Size
{
    public function __construct(
        protected array|string $size,
        protected ?SizeMap     $map = null,
    ) {
    }

    public function size(): array|string
    {
        return $this->size;
    }

    public function map(): ?SizeMap
    {
        return $this->map;
    }
}
