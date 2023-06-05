<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent as PMCommandEvent;
use pocketmine\player\Player;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\Loader;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class CommandEvent implements Listener {

    public function __construct(Loader $plugin){
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMCommandEvent::class,
            \Closure::fromCallable([$this, "onCommand"]),
            EventPriority::HIGHEST,
            $plugin
        );
    }

    public function onCommand(PMCommandEvent $event): void{
        $player = $event->getSender();
        if(!$player instanceof Player) return;
        $world = $player->getWorld();
        $command = $event->getCommand();
        $worldCommandBanned = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::BAN_COMMAND
        );
        $commandMap = $player->getServer()->getCommandMap()->getCommand($command);
        if($commandMap === null) return;
        $permissions = $commandMap->getPermissions();
        foreach ($permissions as $permission) {
            var_dump($permission);
            if (in_array($permission, $worldCommandBanned)) {
                $player->sendMessage(
                    message: LanguageManager::getTranslation(
                        key: KnownTranslations::WORLD_BAN_COMMAND,
                        replacements: [
                            TranslationKeys::WORLD => $world->getDisplayName(),
                            TranslationKeys::COMMAND => $command
                        ]
                    )
                );
                $event->cancel();
            }
        }
    }
}