<?php

namespace AdDirector\Tests\Fixtures;

use AdDirector\Facades\AdDirector;
use AdDirector\GPT\SizeMap;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function boot()
    {
        AdDirector::gpt()
            // Add size with mapping example
                  ::addSize('leaderboard', new \AdDirector\GPT\Size(
                      // Allowed sizes
                      [[728, 90], [320, 50]],
                      // Responsive size map
                      (new SizeMap())
                          // Tablet
                          ->addSize([1024, 768], [728, 90])
                          // Desktop
                          ->addSize([1050, 200], [728, 90])
                          // Mobile
                          ->addSize([640, 480], [320, 50])
                          // Any size
                          ->addSize([0, 0], [320, 50])
                  ))
            // Add static size without responsive
                  ::addSize('medium_rectangle', new \AdDirector\GPT\Size([300, 250]));
    }
}
