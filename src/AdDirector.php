<?php

namespace AdDirector;

use AdDirector\Contracts\AdManager;

class AdDirector
{
    public function gpt(): \AdDirector\GPT\AdGPT
    {
        return app('ad-gpt-manager');
    }

    protected function getManager(?string $manager = null): ?AdManager
    {
        return match ($manager) {
            'gpt'   => $this->gpt(),
            default => null,
        };
    }

    /**
     * @return \AdDirector\Contracts\AdManager[]
     */
    protected function managers(): array
    {
        return [
            $this->gpt(),
        ];
    }

    public function configurationScript(?string $manager = null): string
    {
        $resultString = '';
        $managers     = is_null($manager) ? $this->managers() : array_filter([$this->getManager($manager)]);
        /** @var \AdDirector\Contracts\AdManager $manager */
        foreach ($managers as $manager) {
            $resultString .= $manager->configurationScript().PHP_EOL;
        }

        return $resultString;
    }

    public function displayScript(?string $manager = null): string
    {
        $resultString = '';
        $managers     = is_null($manager) ? $this->managers() : array_filter([$this->getManager($manager)]);
        /** @var \AdDirector\Contracts\AdManager $manager */
        foreach ($managers as $manager) {
            $resultString .= $manager->displayScript().PHP_EOL;
        }

        return $resultString;
    }

    public function adLocation(string $name, ?string $manager = null): string
    {
        $managers = is_null($manager) ? $this->managers() : array_filter([$this->getManager($manager)]);
        /** @var \AdDirector\Contracts\AdManager $manager */
        foreach ($managers as $manager) {
            if ($resultString = $manager->adLocation($name)) {
                return $resultString;
            }
        }

        return '';
    }
}
