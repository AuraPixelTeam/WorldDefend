<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use taylordevs\WorldDefend\commands\subcmds\AntiDecay;
use taylordevs\WorldDefend\commands\subcmds\BanCommand;
use taylordevs\WorldDefend\commands\subcmds\BanItem;
use taylordevs\WorldDefend\commands\subcmds\Build;
use taylordevs\WorldDefend\commands\subcmds\Gamemode;
use taylordevs\WorldDefend\commands\subcmds\KeepExperience;
use taylordevs\WorldDefend\commands\subcmds\KeepInventory;
use taylordevs\WorldDefend\commands\subcmds\PvP;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\Loader;

class WorldDefend extends Command implements PluginOwned {

    public function __construct(protected Loader $plugin){
        parent::__construct(
            name: "worlddefend",
            description: "WorldDefend command",
            usageMessage: "/worlddefend",
            aliases: ["wd"]
        );
        $this->setPermission("worlddefend.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(
                LanguageManager::getTranslation(
                    key: KnownTranslations::COMMAND_PERMISSION,
                )
            );
        }
        $subcmd = isset($args[0]) ? strtolower(array_shift($args)) : null;
        match ($subcmd) {
            'build' => new Build($sender, $args),
            'pvp' => new PvP($sender, $args),
            'antidecay' => new AntiDecay($sender, $args),
            'keepinventory', 'keepinv' => new KeepInventory($sender, $args),
            'keepexperience', 'keepexp' => new KeepExperience($sender, $args),
            'banitem' => new BanItem($sender, $args, 'ban'),
            'unbanitem' => new BanItem($sender, $args, 'unban'),
            'bancommand', 'bancmd' => new BanCommand($sender, $args, 'ban'),
            'unbancommand', 'unbancmd' => new BanCommand($sender, $args, 'unban'),
            'gamemode', 'gm' => new Gamemode($sender, $args),
            default => $sender->sendMessage(
                LanguageManager::getTranslation(
                    key: KnownTranslations::COMMAND_USAGE
                )
            ),
        };
    }

    /**
     * @return Loader
     */
    public function getOwningPlugin(): Plugin
    {
        return $this->plugin;
    }
}