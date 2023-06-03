<?php

declare(strict_types=1);

namespace taylordevs\WorldDefend\world;

enum WorldProperty {
    const BUILD = "build";
    const PVP = "pvp";
    const NO_DECAY = "no-decay";
    const KEEP_INVENTORY = "keep-inventory";
    const KEEP_EXPERIENCE = "keep-experience";
    const BAN_ITEM = "item-ban";
    const BAN_COMMAND = "cmd-ban";
}