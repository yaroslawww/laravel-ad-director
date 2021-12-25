<?php

namespace AdDirector\Contracts;

interface AdManager
{
    /**
     * Return configuration script.
     *
     * @return string
     */
    public function configurationScript(): string;

    /**
     * Return display script.
     *
     * @return string
     */
    public function displayScript(): string;

    /**
     * Return ad location html code.
     *
     * @param  string  $name
     * @return string
     */
    public function adLocation(string $name): string;
}
