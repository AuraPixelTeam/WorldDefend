<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\language;

use taylordevs\WorldDefend\Loader;

class LanguageManager {

    private const SUPPORTED_LANGUAGE = [
      "en-US",
      "vi-VN"
    ];

    private const DEFAULT_LANGUAGE = "en-US";

    protected static string $language;

    protected static array $languageData;

    public static function init(Loader $plugin, ?string $defaultLanguage): void {
        $languagePath = $plugin->getDataFolder() . "languages/";
        if(!is_dir($languagePath)) {
            @mkdir($languagePath);
        }
        $language = (!$defaultLanguage ||
            !in_array($defaultLanguage, self::SUPPORTED_LANGUAGE)
        ) ? self::DEFAULT_LANGUAGE : $defaultLanguage;

        foreach (self::SUPPORTED_LANGUAGE as $languageCode) {
            if(!file_exists($languagePath . $languageCode . ".yml")) {
                $plugin->saveResource("languages" . DIRECTORY_SEPARATOR . $languageCode . ".yml");
            }
        }

        LanguageManager::$language = $defaultLanguage;
        LanguageManager::$languageData = yaml_parse_file($languagePath . $language . ".yml");
    }

    public static function getLanguage(): string {
        return LanguageManager::$language;
    }

    public static function hasTranslation(string $key): bool {
        return isset(LanguageManager::$languageData[$key]);
    }

    public static function getTranslation(string $key, array $replacements = []): string {
        return LanguageManager::hasTranslation($key) ? LanguageManager::replaceArgs($key, $replacements) : $key;
    }

    protected static function replaceArgs(string $key, array $args): string {
        return str_replace(array_keys($args), array_values($args), LanguageManager::$languageData[$key]);
    }
}