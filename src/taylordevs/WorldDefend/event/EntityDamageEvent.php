<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent as PMEntityDamageEvent;
use pocketmine\player\Player;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class EntityDamageEvent
{

    public function onEntityDamage(PMEntityDamageEvent $event): void {
        if ($event->isCancelled()) return;
        if (!$event instanceof EntityDamageByEntityEvent) return;
        $player = $event->getEntity();
        $damage = $event->getDamager();
        if (
            !$player instanceof Player &&
            !$damage instanceof Player
        ) return;
        $world = $player->getWorld();
        $pvp = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::PVP
        );
        if ($pvp) {
            $player->sendMessage(
                message: LanguageManager::getTranslation(
                    key: KnownTranslations::WORLD_PVP,
                    replacements: [
                        TranslationKeys::WORLD => $world->getDisplayName()
                    ]
                )
            );
            $event->cancel();
        }
    }
}