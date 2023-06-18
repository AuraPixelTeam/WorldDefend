<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend;

use pocketmine\plugin\PluginBase;
use taylordevs\WorldDefend\commands\WorldDefend;
use taylordevs\WorldDefend\event\BlockBreakEvent;
use taylordevs\WorldDefend\event\BlockPlaceEvent;
use taylordevs\WorldDefend\event\CommandEvent;
use taylordevs\WorldDefend\event\EntityDamageEvent;
use taylordevs\WorldDefend\event\EntityTrampleEvent;
use taylordevs\WorldDefend\event\PlayerDeathEvent;
use taylordevs\WorldDefend\event\PlayerItemUseEvent;
use taylordevs\WorldDefend\event\WorldPerGModeEvent;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\world\WorldManager;

class Loader extends PluginBase {

    protected const EVENTS = [
        BlockBreakEvent::class,
        BlockPlaceEvent::class,
        CommandEvent::class,
        EntityDamageEvent::class,
        EntityTrampleEvent::class,
        PlayerDeathEvent::class,
        PlayerItemUseEvent::class,
        WorldPerGModeEvent::class
    ];

    /**
     * @throws \JsonException
     */
    protected function onEnable(): void
    {
        $this->saveDefaultConfig();
        LanguageManager::init($this, $this->getConfig()->get("defaultLanguage", null));
        WorldManager::init();
        foreach (self::EVENTS as $event) {
            new $event($this);
        }
        $this->getServer()->getCommandMap()->register(
            fallbackPrefix: "worlddefend",
            command: new WorldDefend($this)
        );
    }
}