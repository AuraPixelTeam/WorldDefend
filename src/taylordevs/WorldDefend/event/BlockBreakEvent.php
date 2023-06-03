<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\block\BlockBreakEvent as PMBlockBreakEvent;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\Loader;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class BlockBreakEvent implements Listener {

    public function __construct(Loader $plugin){
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMBlockBreakEvent::class,
            \Closure::fromCallable([$this, "onBreakBlock"]),
            EventPriority::HIGHEST,
            $plugin
        );
    }

    public function onBreakBlock(PMBlockBreakEvent $event): void{
        $world = $event->getBlock()->getPosition()->getWorld();
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