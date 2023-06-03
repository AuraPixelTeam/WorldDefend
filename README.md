<h1>WorldDefend<img src="icon.png" height="64" width="64" align="left"></h1><br/>

[![Lint](https://poggit.pmmp.io/ci.shield/Taylor-pm-pl/WorldDefend/WorldDefend)](https://poggit.pmmp.io/ci/Taylor-pm-pl/WorldDefend/WorldDefend)
[![Discord](https://img.shields.io/discord/1100650029573738508.svg?label=&logo=discord&logoColor=ffffff&color=7389D8&labelColor=6A7EC2)](https://discord.gg/yAhsgskaGy)

**NOTICE:** This plugin branch is for PocketMine-MP 5. <br/>
✨ **Protect your world from grief, break, pvp, command, item, decay.**

## Features
- [x] Per world settings
- [x] Protect world from break/place
- [x] Per world pvp
- [x] Ban items in world
- [x] Ban commands in world
- [x] Anti farm land decay in world

## Commands
- Default command: `/worlddefend`
- Aliases: `/wd`

| Command          | Description                       |
|------------------|-----------------------------------|
| `/wd`            | Show help                         |
| `/wd build`      | Locks world, not even Op can use. |
| `/wd pvp`        | Enable/disable pvp in world.      |
| `/wd antidecay`  | Enable/disable decay in world.    |
| `/wd keepinventory` | Enable/disable keep inventory.    |
| `/wd keepexperience`       | Enable/disable keep experience.   |
| `/wd banitem`    | Ban item in world.                |
| `/wd unbanitem`  | Unban item in world.              |
| `/wd bancmd`     | Ban command in world.             |
| `/wd unbancmd`   | Unban command in world.           |

## Permissions
| Permission       | Description                       |
|------------------|-----------------------------------|
| `worlddefend.command` | Allow use all commands.       |

## Documentation
- This plugin allows you to protect your world from grief, break, pvp, command, item, decay.
- For example, you can use `/wd build world true` to prevent other players from destroying your world named `world`.
- You can also use /wd pvp world false to disable pvp in world.
- You Want to keep a world-only inventory called `MyStuff`? don't worry WorldDefend can also help you just `/wd keepinv MyStuff true` immediately that world will keep your inventory when you die.
- You hate other people using wheat seed in the world called `SkyBlock` without knowing its `TypeId`? It's okay just hold the wheat seed in your hand and use `/wd banitem SkyBlock`

## TODO
- [ ] World Border
- [ ] Per world gamemode
- [ ] Limit player in world
- [ ] No explode in world

## Translation
This plugin will honour the server language configuration. The languages currently available are:
- English (en-US)
- Vietnamese (vi-VN)
We would love to receive contributions on the missing languages or corrections on the existing ones. Please read [CONTRIBUTING.md](CONTRIBUTING.md) on how to do so.

## License
This plugin is licensed under the [MIT License](LICENSE).