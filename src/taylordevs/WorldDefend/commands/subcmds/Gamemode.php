<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\commands\subcmds;

use pocketmine\command\CommandSender;
use pocketmine\command\defaults\GamemodeCommand;
use pocketmine\player\GameMode as PGameMode;
use pocketmine\Server;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class Gamemode
{

    public function __construct(CommandSender $sender, array $args){
        $this->execute($sender, $args);
    }

    protected function execute(CommandSender $sender, array $args): void {
        $world = Server::getInstance()->getWorldManager()->getWorldByName($args[0] ?? "");
        if (!isset($args[1])) {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    KnownTranslations::COMMAND_GAMEMODE_USAGE
                )
            );
            return;
        }
        $value = ($args[1] !== "false") ? strval($args[1]) : false;
        if ($value !== false) {
            $GameMode = PGameMode::fromString($value);
            if ($GameMode === null) {
                $sender->sendMessage(
                    LanguageManager::getTranslation(
                        KnownTranslations::COMMAND_GAMEMODE_NOT_FOUND,
                        [
                            TranslationKeys::VALUE => $value
                        ]
                    )
                );
                return;
            }
        }
        if (is_null($world)) {
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
        WorldManager::setProperty(
            world: $world,
            property: WorldProperty::GAMEMODE,
            value: $value
        );
        $sender->sendMessage(
            LanguageManager::getTranslation(
                KnownTranslations::COMMAND_GAMEMODE_SUCCESS,
                [
                    TranslationKeys::WORLD => $world->getDisplayName(),
                    TranslationKeys::VALUE => !$value ? "off" : $GameMode->getEnglishName()
                ]
            )
        );
    }
}