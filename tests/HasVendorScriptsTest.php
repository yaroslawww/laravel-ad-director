<?php

namespace AdDirector\Tests;

use AdDirector\Facades\AdGPT;

class HasVendorScriptsTest extends TestCase
{
    /** @test */
    public function clear_and_override_sizes()
    {
        $this->assertTrue(AdGPT::isImportVendorScripts());

        AdGPT::dontImportVendorScripts();
        $this->assertFalse(AdGPT::isImportVendorScripts());

        AdGPT::dontImportVendorScripts(true);
        $this->assertTrue(AdGPT::isImportVendorScripts());
    }
}
