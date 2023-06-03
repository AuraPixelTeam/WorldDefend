<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\world;

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\World;

class WorldManager
{

    protected const CONFIG_VERSION = "1.0.0";
    protected static array $worlds = [];
    protected const BOOLEAN_PROPERTIES = [
        "enabled",
        "lock",
        "pvp",
        "no-decay",
        "save-inventory",
        "save-xp"
    ];
    protected const ARRAY_PROPERTIES = [
        "item-ban",
        "cmd-ban"
    ];

    protected static function isLoaded(string $worldName): bool
    {
        return isset(WorldManager::$worlds[$worldName]);
    }

    public static function init(): void
    {
        $worlds = Server::getInstance()->getWorldManager()->getWorlds();
        foreach ($worlds as $world) {
            if ($world instanceof World) $world = $world->getDisplayName();
            if (WorldManager::isLoaded($world)) return;

            if (Server::getInstance()->getWorldManager()->isWorldLoaded($world)) {
                $worldPath = Server::getInstance()->getDataPath() . "worlds" . DIRECTORY_SEPARATOR . $world . DIRECTORY_SEPARATOR;
                if (!is_dir($worldPath)) continue;
            } else {
                $world = Server::getInstance()->getWorldManager()->getWorldByName($world);
                if (!$world instanceof World) return;
                $worldPath = $world->getProvider()->getPath();
            }

            $configPath = $worldPath . "worlddefend.yml";
            if (!file_exists($configPath)) {
                $config = new Config($configPath, Config::YAML, [
                    "enabled" => true,
                    "lock" => false,
                    "pvp" => true,
                    "no-decay" => false,
                    "keep-inventory" => false,
                    "keep-experience" => false,
                    "item-ban" => [],
                    "cmd-ban" => [],
                    "config-version" => WorldManager::CONFIG_VERSION
                ]);
                $config->save();
                WorldManager::$worlds[$world] = $config;
            } else {
                $config = new Config($configPath, Config::YAML);
                if ($config->get("config-version") !== WorldManager::CONFIG_VERSION) {
                    // TODO: Update config
                }
                if ($config->get("enabled", true)) {
                    WorldManager::$worlds[$world] = $config;
                }
            }
        }
    }

    public static function getProperty(World $world, string $property): bool|array
    {
        $worldName = $world->getDisplayName();
        if (!WorldManager::isLoaded($worldName)) return false;
        return WorldManager::$worlds[$worldName]->get($property, false);
    }

    public static function setProperty(World $world, string $property, bool $value): void
    {
        $worldName = $world->getDisplayName();
        if (!WorldManager::isLoaded($worldName)) return;
        if (in_array($property, WorldManager::ARRAY_PROPERTIES)) return;
        if (in_array($property, WorldManager::BOOLEAN_PROPERTIES)) {
            WorldManager::$worlds[$worldName]->set($property, $value);
            WorldManager::$worlds[$worldName]->save();
        }
    }

    public static function addProperty(World $world, string $property, $value): bool
    {
        $worldName = $world->getDisplayName();
        if (!WorldManager::isLoaded($worldName)) return false;
        if (!in_array($property, WorldManager::ARRAY_PROPERTIES)) return false;
        $array = WorldManager::getProperty($world, $property);
        if (!is_array($array)) return false;
        if (!in_array($value, $array)) {
            $array[] = $value;
            WorldManager::$worlds[$worldName]->set($property, $array);
            WorldManager::$worlds[$worldName]->save();
            return true;
        }
        return false;
    }
}