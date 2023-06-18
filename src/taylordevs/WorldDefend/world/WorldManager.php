<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\world;

use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\world\World;

class WorldManager {

    protected static array $worlds = [];
    protected const BOOLEAN_PROPERTIES = [
        WorldProperty::BUILD,
        WorldProperty::PVP,
        WorldProperty::NO_DECAY,
        WorldProperty::KEEP_INVENTORY,
        WorldProperty::KEEP_EXPERIENCE
    ];

    protected const STRING_PROPERTIES = [
        WorldProperty::GAMEMODE
    ];

    protected const ARRAY_PROPERTIES = [
        WorldProperty::BAN_ITEM,
        WorldProperty::BAN_COMMAND
    ];

    protected static function isLoaded(string $worldName): bool
    {
        return isset(WorldManager::$worlds[$worldName]);
    }

    /**
     * @throws \JsonException
     */
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
            $config = WorldManager::createConfig($configPath);
            $config->save();
            WorldManager::$worlds[$world] = $config;
        }
    }

    public static function getProperty(World $world, string $property): string|bool|array
    {
        $worldName = $world->getDisplayName();
        if (!WorldManager::isLoaded($worldName)) return false;
        return WorldManager::$worlds[$worldName]->get($property, false);
    }

    public static function setProperty(World $world, string $property, string|bool $value): void
    {
        $worldName = $world->getDisplayName();
        if (!WorldManager::isLoaded($worldName)) return;
        if (in_array($property, WorldManager::ARRAY_PROPERTIES)) return;
        if (
            in_array($property, WorldManager::BOOLEAN_PROPERTIES) ||
            in_array($property, WorldManager::STRING_PROPERTIES)
        ) {
            WorldManager::$worlds[$worldName]->set($property, $value);
            WorldManager::$worlds[$worldName]->save();
        }
    }

    public static function addProperty(World $world, string $property, mixed $value): bool
    {
        $worldName = $world->getDisplayName();
        if (!WorldManager::isLoaded($worldName)) return false;
        if (!in_array($property, WorldManager::ARRAY_PROPERTIES)) return false;
        $array = WorldManager::getProperty($world, $property);
        if (!is_array($array)) return false;
        if (!in_array($value, $array)) {
            $array[] = $value;
            WorldManager::$worlds[$worldName]->set($property, array_values($array));
            WorldManager::$worlds[$worldName]->save();
            return true;
        }
        return false;
    }

    public static function removeProperty(World $world, string $property, mixed $value): bool
    {
        $worldName = $world->getDisplayName();
        if (!WorldManager::isLoaded($worldName)) return false;
        if (!in_array($property, WorldManager::ARRAY_PROPERTIES)) return false;
        $array = WorldManager::getProperty($world, $property);
        if (!is_array($array)) return false;
        if (in_array($value, $array)) {
            $key = array_search($value, $array);
            unset($array[$key]);
            WorldManager::$worlds[$worldName]->set($property, array_values($array));
            WorldManager::$worlds[$worldName]->save();
            return true;
        }
        return false;
    }

    protected static function createConfig(string $configPath): Config {
        return new Config($configPath, Config::YAML, [
            WorldProperty::BUILD => false,
            WorldProperty::PVP => false,
            WorldProperty::NO_DECAY => false,
            WorldProperty::KEEP_INVENTORY => false,
            WorldProperty::KEEP_EXPERIENCE => false,
            WorldProperty::GAMEMODE => false,
            WorldProperty::BAN_ITEM => [],
            WorldProperty::BAN_COMMAND => []
        ]);
    }
}