<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\event;

use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent as PMPlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent as PMPlayerItemUseEvent;
use pocketmine\event\block\BlockBreakEvent as PMBlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\world\World;
use taylordevs\WorldDefend\language\KnownTranslations;
use taylordevs\WorldDefend\language\LanguageManager;
use taylordevs\WorldDefend\language\TranslationKeys;
use taylordevs\WorldDefend\Loader;
use taylordevs\WorldDefend\world\WorldManager;
use taylordevs\WorldDefend\world\WorldProperty;

class PlayerItemUseEvent implements Listener {

    public function __construct(Loader $plugin){
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMPlayerItemUseEvent::class,
            \Closure::fromCallable([$this, "onItemUse"]),
            EventPriority::HIGHEST,
            $plugin
        );
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMPlayerInteractEvent::class,
            \Closure::fromCallable([$this, "onInteract"]),
            EventPriority::HIGHEST,
            $plugin
        );
        $plugin->getServer()->getPluginManager()->registerEvent(
            PMBlockBreakEvent::class,
            \Closure::fromCallable([$this, "onBreak"]),
            EventPriority::HIGHEST,
            $plugin
        );
    }

    public function onItemUse(PMPlayerItemUseEvent $event): void {
        $this->testItem($event);
    }

    public function onInteract(PMPlayerInteractEvent $event): void {
        $this->testItem($event);
    }

    public function onBreak(PMBlockBreakEvent $event): void {
        $this->testItem($event);
    }

    protected function testItem(PMPlayerItemUseEvent|PMPlayerInteractEvent|PMBlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $world = $player->getWorld();
        $itemAliases = StringToItemParser::getInstance()->lookupAliases($event->getItem());
        $worldItemBanned = WorldManager::getProperty(
            world: $world,
            property: WorldProperty::BAN_ITEM
        );
        if (is_array($worldItemBanned)) {
            if (!empty(array_intersect($itemAliases, $worldItemBanned))) {
                $player->sendMessage(
                    message: LanguageManager::getTranslation(
                        key: KnownTranslations::WORLD_BAN_ITEM,
                        replacements: [
                            TranslationKeys::WORLD => $world->getDisplayName(),
                            TranslationKeys::ITEM => $event->getItem()->getName()
                        ]
                    )
                );
                $event->cancel();
            }
        }
    }
}