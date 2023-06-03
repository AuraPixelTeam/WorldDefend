<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\entity\EntityTrampleFarmlandEvent;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class EntityTrampleEvent {

    public function onEntityTrample(EntityTrampleFarmlandEvent $event): void {
        $player = $event->getEntity();
        $world = $player->getWorld();
        $trample = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::NO_DECAY
        );
        if ($trample) {
            $event->cancel();
        }
    }
}