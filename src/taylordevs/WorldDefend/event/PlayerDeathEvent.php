<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\player\PlayerDeathEvent as PMPlayerDeathEvent;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\world\WorldManager;

class PlayerDeathEvent {

    public function onDeath(PMPlayerDeathEvent $event): void {
        $player = $event->getPlayer();
        $world = $player->getWorld();
        $keepInventory = WorldManager::getProperty(
            world: $world,
            property: "keep-inventory"
        );
        $keepExperience = WorldManager::getProperty(
            world: $world,
            property: "keep-experience"
        );
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