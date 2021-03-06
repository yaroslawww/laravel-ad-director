<?php

namespace AdDirector\Tests;

use AdDirector\Facades\AdDirector;
use AdDirector\GPT\Presenter;
use AdDirector\GPT\SizeMap;
use AdDirector\GPT\Slot;

class AdGPTTest extends TestCase
{

    /** @test */
    public function use_reference_to_global_size()
    {
        $adLocationName = 'header-ads';
        $divId          = 'div-gpt-ad-1234567890001-0';

        AdDirector::gpt()
                  ->addLocation(Slot::make(
                      '/1234567/FOO_Leaderboard',
                      'leaderboard',
                      $divId
                  ), $adLocationName);

        $returnConfigurationScripts   = [];
        $returnConfigurationScripts[] = AdDirector::configurationScript();
        $returnConfigurationScripts[] = AdDirector::configurationScript('gpt');
        foreach ($returnConfigurationScripts as $configurationScript) {
            $this->assertStringContainsString(Presenter::$jsMapPrefix.'leaderboard.addSize(', $configurationScript);
            $this->assertStringContainsString(Presenter::$jsMapPrefix.'leaderboard.build()', $configurationScript);
            $this->assertStringContainsString('defineSizeMapping('.Presenter::$jsMapPrefix.'leaderboard)', $configurationScript);
        }

        $returnDisplayScripts   = [];
        $returnDisplayScripts[] = AdDirector::displayScript();
        $returnDisplayScripts[] = AdDirector::displayScript('gpt');
        foreach ($returnDisplayScripts as $displayScript) {
            $this->assertStringContainsString("googletag.display('{$divId}')", $displayScript);
        }

        $returnAdLocations   = [];
        $returnAdLocations[] = AdDirector::adLocation($adLocationName);
        $returnAdLocations[] = AdDirector::adLocation($adLocationName, 'gpt');
        foreach ($returnAdLocations as $adLocation) {
            $this->assertStringContainsString("<div id='{$divId}'>", $adLocation);
        }
    }

    /** @test */
    public function use_manual_size()
    {
        $divId = 'div-gpt-ad-1234567890001-0';
        $slot  = Slot::make(
            '/1234567/FOO_Leaderboard',
            new \AdDirector\GPT\Size([100, 200], (new SizeMap())
                ->addSize([0, 0], [100, 200])),
            $divId
        );

        AdDirector::gpt()->addLocation($slot);

        $configurationScript = AdDirector::configurationScript('gpt');
        $this->assertStringContainsString(Presenter::$jsMapPrefix.Presenter::prepareSizeMapVariableName($divId).'.addSize(', $configurationScript);
        $this->assertStringContainsString(Presenter::$jsMapPrefix.Presenter::prepareSizeMapVariableName($divId).'.build()', $configurationScript);
        $this->assertStringContainsString('defineSizeMapping('.Presenter::$jsMapPrefix.Presenter::prepareSizeMapVariableName($divId).')', $configurationScript);

        $displayScript = AdDirector::displayScript('gpt');
        $this->assertStringContainsString("googletag.display('{$divId}')", $displayScript);

        $adLocation = AdDirector::adLocation($slot->adUnitPath(), 'gpt');
        $this->assertStringContainsString("<div id='{$divId}'>", $adLocation);
    }

    /** @test */
    public function ad_location_returns_empty_if_not_found()
    {
        $this->assertEmpty(AdDirector::adLocation('foo'));
        $this->assertIsString(AdDirector::adLocation('foo'));
    }

    /** @test */
    public function autogenerated_div_id()
    {
        $adLocationName = 'header-ads';

        $slot = Slot::make(
            '/1234567/FOO_Leaderboard',
            'leaderboard'
        );
        AdDirector::gpt()
                  ->addLocation($slot, $adLocationName);

        $configurationScript = AdDirector::configurationScript('gpt');
        $this->assertStringContainsString(Presenter::$jsMapPrefix.'leaderboard.addSize(', $configurationScript);
        $this->assertStringContainsString(Presenter::$jsMapPrefix.'leaderboard.build()', $configurationScript);
        $this->assertStringContainsString('defineSizeMapping('.Presenter::$jsMapPrefix.'leaderboard)', $configurationScript);
        $this->assertStringContainsString("googletag.defineSlot('{$slot->adUnitPath()}'", $configurationScript);

        $displayScript = AdDirector::displayScript('gpt');
        $this->assertStringContainsString("googletag.display('{$slot->divId()}')", $displayScript);

        $adLocation = AdDirector::adLocation($adLocationName, 'gpt');
        $this->assertStringContainsString("<div id='{$slot->divId()}'>", $adLocation);
    }

    /** @test */
    public function manipulate_slot_with_targets()
    {
        $slot = Slot::make(
            '/1234567/FOO_Leaderboard',
            'leaderboard'
        );

        $this->assertCount(0, $slot->targets());

        $slot->addTarget('foo', 'bar')
             ->addTarget('baz', ['foo', 'bar'])
             ->addTarget('third', 'val');

        $this->assertCount(3, $slot->targets());

        $slot->clearTargets('foo');
        $this->assertCount(2, $slot->targets());
        $this->assertEquals('baz,third', implode(',', array_keys($slot->targets())));

        $slot->clearTargets(['third']);
        $this->assertCount(1, $slot->targets());
        $this->assertEquals('baz', implode(',', array_keys($slot->targets())));

        $slot->clearTargets();
        $this->assertCount(0, $slot->targets());
    }

    /** @test */
    public function slot_cant_be_usd_without_size()
    {
        $adLocationName = 'header-ads';

        $slot = Slot::make(
            '/1234567/FOO_Leaderboard',
            'fake-size'
        );
        AdDirector::gpt()
                  ->addLocation($slot, $adLocationName);

        $configurationScript = AdDirector::configurationScript('gpt');
        $this->assertStringNotContainsString("googletag.defineSlot('{$slot->adUnitPath()}'", $configurationScript);

        $displayScript = AdDirector::displayScript('gpt');
        $this->assertStringContainsString("googletag.display('{$slot->divId()}')", $displayScript);

        $adLocation = AdDirector::adLocation($adLocationName, 'gpt');
        $this->assertStringContainsString("<div id='{$slot->divId()}'>", $adLocation);
    }
}
