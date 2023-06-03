<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\entity\EntityTrampleFarmlandEvent;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use taylordevs\WorldDefend\Loader;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class EntityTrampleEvent implements Listener {

    public function __construct(Loader $plugin){
        $plugin->getServer()->getPluginManager()->registerEvent(
            EntityTrampleFarmlandEvent::class,
            \Closure::fromCallable([$this, "onEntityTrample"]),
            EventPriority::HIGHEST,
            $plugin
        );
    }

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