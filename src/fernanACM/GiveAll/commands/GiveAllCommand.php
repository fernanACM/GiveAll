<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
  
namespace fernanACM\GiveAll\commands;

use pocketmine\player\Player;

use pocketmine\utils\TextFormat;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseCommand;

use fernanACM\GiveAll\GiveAll;
use fernanACM\GiveAll\utils\PermissionsUtils;
use fernanACM\GiveAll\utils\PluginUtils;

use fernanACM\GiveAll\commands\subcommands\EditSubCommand;
use fernanACM\GiveAll\commands\subcommands\GiveAllGUISubCommand;
use fernanACM\GiveAll\commands\subcommands\GiveAllFormSubCommand;
use fernanACM\GiveAll\commands\subcommands\SendSubCommand;

class GiveAllCommand extends BaseCommand{

    public function __construct(){
        parent::__construct(GiveAll::getInstance(), "giveall", "GiveAll items for players by fernanACM", ["gall"]);
        $this->setPermission(PermissionsUtils::GIVEALL_CMD);
    }

    /**
     * @return void
     */
    protected function prepare(): void{
        $this->registerSubCommand(new GiveAllFormSubCommand);
        $this->registerSubCommand(new GiveAllGUISubCommand);
        $this->registerSubCommand(new EditSubCommand);
        $this->registerSubCommand(new SendSubCommand);
    }

    /**
     * @param CommandSender $sender
     * @param string $aliasUsed
     * @param array $args
     * @return void
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
        if(!$sender instanceof Player){
            $sender->sendMessage("Use this command in-game");
            return;
        }
        if(!$sender->hasPermission(PermissionsUtils::GIVEALL_CMD)){
            $sender->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($sender, "Messages.error.no-permission"));
            PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
            return;
        }
        $sender->sendMessage(TextFormat::colorize("&l&cGIVEALL - COMMAND LIST"));
        $sender->sendMessage(TextFormat::colorize("&e/giveall form:&7 GiveAll in Form"));
        $sender->sendMessage(TextFormat::colorize("&e/giveall gui:&7 GiveAll in GUI"));
        $sender->sendMessage(TextFormat::colorize("&e/giveall send <amount: 1,2,3...>:&7 Automatic GiveAll"));
        $sender->sendMessage(TextFormat::colorize("&e/giveall edit:&7 Edit the inventory of the automatic GiveAll"));
        $sender->sendMessage(TextFormat::colorize("\n&bAuthor:&a fernanACM"));
        $sender->sendMessage(TextFormat::colorize("&bGithub:&a https://github.com/fernanACM"));
        $sender->sendMessage(TextFormat::colorize("&bYoutube:&a fernanACM"));
        $sender->sendMessage(TextFormat::colorize("&bDiscord:&a https://discord.gg/YyE9XFckqb"));
    }
}