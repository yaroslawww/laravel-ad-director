<?php

namespace AdDirector;

class RenderMinifier
{
    public static function minify(string $content):string
    {
        return str_replace(PHP_EOL, '', $content);
    }
}
