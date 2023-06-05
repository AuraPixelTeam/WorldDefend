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

class BanCommand
{

    public function __construct(CommandSender $sender, array $args, string $type){
        $this->execute($sender, $args, $type);
    }

    protected function execute(CommandSender $sender, array $args, string $type): void {
        $world = Server::getInstance()->getWorldManager()->getWorldByName($args[0] ?? "");
        $value = $args[1] ?? "";
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
        if (
            $value === null ||
            $value === ""
        ) {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    KnownTranslations::COMMAND_BAN_COMMAND_USAGE
                )
            );
            return;
        }
        $commandMap = Server::getInstance()->getCommandMap()->getCommand($value);
        if ($commandMap === null) {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    KnownTranslations::COMMAND_BAN_COMMAND_NOT_FOUND,
                    [
                        TranslationKeys::COMMAND => $value
                    ]
                )
            );
            return;
        }
        if ($type === "ban") {
            foreach ($commandMap->getPermissions() as $permission) {
                WorldManager::addProperty(
                    world: $world,
                    property: WorldProperty::BAN_COMMAND,
                    value: $permission
                );
            }
        } elseif ($type === "unban") {
            foreach ($commandMap->getAliases() as $permission) {
                WorldManager::removeProperty(
                    world: $world,
                    property: WorldProperty::BAN_COMMAND,
                    value: $permission
                );
            }
        }
        $sender->sendMessage(
            LanguageManager::getTranslation(
                ($type === "ban") ? KnownTranslations::COMMAND_BAN_COMMAND_SUCCESS : KnownTranslations::COMMAND_UNBAN_COMMAND_SUCCESS,
                [
                    TranslationKeys::WORLD => $world->getDisplayName(),
                    TranslationKeys::COMMAND => $value
                ]
            )
        );
    }
}