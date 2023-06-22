<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent as PMPlayerDeathEvent;
use ReflectionException;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\Loader;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class PlayerDeathEvent implements Listener {

    /**
     * @throws ReflectionException
     */
    public function __construct(Loader $plugin){
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMPlayerDeathEvent::class,
            $this->onDeath(...),
            EventPriority::HIGHEST,
            $plugin
        );
    }

    public function onDeath(PMPlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $world = $player->getWorld();
        $keepInventory = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::KEEP_INVENTORY
        ) ?? false;
        $keepExperience = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::KEEP_EXPERIENCE
        ) ?? false;
        if ($keepInventory) {
            $event->setKeepInventory(true);
            $player->sendMessage(
                message: LanguageManager::getTranslation(
                    key: KnownTranslations::WORLD_KEEP_INVENTORY,
                    replacements: [
                        TranslationKeys::WORLD => $world->getDisplayName()
                    ]
                )
            );
        }
        if ($keepExperience) {
            $event->setKeepXp(true);
            $player->sendMessage(
                message: LanguageManager::getTranslation(
                    key: KnownTranslations::WORLD_KEEP_EXPERIENCE,
                    replacements: [
                        TranslationKeys::WORLD => $world->getDisplayName()
                    ]
                )
            );
        }
    }
}