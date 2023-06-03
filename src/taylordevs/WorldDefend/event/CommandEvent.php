<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\server\CommandEvent as PMCommandEvent;
use pocketmine\player\Player;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\world\WorldManager;

class CommandEvent
{

    public function onCommand(PMCommandEvent $event): void{
        $player = $event->getSender();
        if(!$player instanceof Player) return;
        $world = $player->getWorld();
        $commandLine = trim($event->getCommand());
        if ($commandLine === "") return;
        $commandLine = preg_split("/\s+/", $commandLine);
        $command = strtolower($commandLine[0] ?? '');
        $worldCommandBanned = WorldManager::getProperty(
            world: $world,
            property: "cmd-ban"
        );
        if (is_array($worldCommandBanned)) {
            if (in_array($command, $worldCommandBanned)) {
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