<?php

namespace AdDirector\Core;

trait HasVendorScripts
{
    protected bool $importVendorScripts = true;

    public function dontImportVendorScripts(bool $importVendorScripts = false): static
    {
        $this->importVendorScripts = $importVendorScripts;

        return $this;
    }

    public function isImportVendorScripts(): bool
    {
        return $this->importVendorScripts;
    }
}
