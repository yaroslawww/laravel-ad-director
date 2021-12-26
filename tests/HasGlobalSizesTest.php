<?php

namespace AdDirector\Tests;

use AdDirector\Facades\AdGPT;

class HasGlobalSizesTest extends TestCase
{
    /** @test */
    public function clear_and_override_sizes()
    {
        $sizes = AdGPT::getSizes();
        $this->assertIsArray($sizes);
        $this->assertCount(2, $sizes);

        AdGPT::clearSizes();
        $sizes = AdGPT::getSizes();
        $this->assertIsArray($sizes);
        $this->assertCount(0, $sizes);

        AdGPT::setSizes([
            'foo_size' => new \AdDirector\GPT\Size('fluid'),
        ]);
        $sizes = AdGPT::getSizes();
        $this->assertIsArray($sizes);
        $this->assertCount(1, $sizes);
    }
}
