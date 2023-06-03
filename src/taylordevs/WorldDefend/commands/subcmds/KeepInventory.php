<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\commands\subcmds;

use pocketmine\command\CommandSender;
use pocketmine\Server;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class KeepInventory
{

    public function __construct(CommandSender $sender, array $args){
        $this->execute($sender, $args);
    }

    protected function execute(CommandSender $sender, array $args): void {
        $world = Server::getInstance()->getWorldManager()->getWorldByName($args[0] ?? "");
        $value = $args[1] ?? null;
        if ($value === "true") {
            $value = true;
        } elseif ($value === "false") {
            $value = false;
        }
        if ($world === null) {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    KnownTranslations::COMMAND_WORLD_NOT_FOUND,
                    [
                        TranslationKeys::WORLD => $args[0] ?? ""
                    ]
                )
            );
            return;
        }
        if ($value === null) {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    KnownTranslations::COMMAND_KEEP_INVENTORY_USAGE
                )
            );
            return;
        }
        WorldManager::setProperty(
            world: $world,
            property: WorldProperty::KEEP_INVENTORY,
            value: $value
        );
        $sender->sendMessage(
            LanguageManager::getTranslation(
                KnownTranslations::COMMAND_KEEP_INVENTORY_SUCCESS,
                [
                    TranslationKeys::WORLD => $world->getDisplayName(),
                    TranslationKeys::VALUE => $value ? "true" : "false"
                ]
            )
        );
    }
}