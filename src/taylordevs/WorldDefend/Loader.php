<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend;

use pocketmine\plugin\PluginBase;
use taylordevs\WorldDefend\language\LanguageManager;

class Loader extends PluginBase {

    protected function onEnable(): void
    {
        $this->saveDefaultConfig();
        LanguageManager::init($this, $this->getConfig()->get("defaultLanguage", null));
    }
}