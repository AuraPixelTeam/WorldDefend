<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\block\BlockPlaceEvent as PMBBlockPlaceEvent;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\Loader;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class BlockPlaceEvent implements Listener {

    public function __construct(Loader $plugin){
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMBlockPlaceEvent::class,
            \Closure::fromCallable([$this, "onPlaceBlock"]),
            EventPriority::HIGHEST,
            $plugin
        );
    }

    public function onPlaceBlock(PMBBlockPlaceEvent $event): void{
        $world = $event->getPlayer()->getWorld();
        $isLock = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::BUILD
        );
        $player = $event->getPlayer();
        if($isLock) {
            $player->sendMessage(
                message: LanguageManager::getTranslation(
                    key: KnownTranslations::WORLD_BUILD,
                    replacements: [
                        TranslationKeys::WORLD => $world->getDisplayName()
                    ]
                )
            );
            $event->cancel();
        }
    }
}