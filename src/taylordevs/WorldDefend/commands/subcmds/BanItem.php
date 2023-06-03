<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\commands\subcmds;

use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\Server;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class BanItem
{

    public function __construct(CommandSender $sender, array $args, string $type){
        $this->execute($sender, $args, $type);
    }

    protected function execute(CommandSender $sender, array $args, string $type): void {
        $world = Server::getInstance()->getWorldManager()->getWorldByName($args[0] ?? "");
        $value = $args[1] ?? "";
        if ($sender instanceof Player) {
            $item = $sender->getInventory()->getItemInHand();
            if (!$item->equals(VanillaItems::AIR())) {
                $value = $item->getTypeId();
            }
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
        if ($value === "") {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    KnownTranslations::COMMAND_BAN_ITEM_USAGE
                )
            );
            return;
        }
        if ($type === "ban") {
            WorldManager::addProperty(
                world: $world,
                property: WorldProperty::BAN_ITEM,
                value: $value
            );
        } elseif ($type === "unban") {
            WorldManager::removeProperty(
                world: $world,
                property: WorldProperty::BAN_ITEM,
                value: $value
            );
        }
        $sender->sendMessage(
            LanguageManager::getTranslation(
                ($type === "ban") ? KnownTranslations::COMMAND_BAN_ITEM_SUCCESS : KnownTranslations::COMMAND_UNBAN_ITEM_SUCCESS,
                [
                    TranslationKeys::WORLD => $world->getDisplayName(),
                    TranslationKeys::ITEM => $value
                ]
            )
        );
    }
}