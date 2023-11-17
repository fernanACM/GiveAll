<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
  
namespace fernanACM\GiveAll\commands\subcommands;

use pocketmine\player\Player;

use pocketmine\utils\TextFormat;

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\args\IntegerArgument;

use fernanACM\GiveAll\GiveAll;
use fernanACM\GiveAll\utils\PermissionsUtils;
use fernanACM\GiveAll\utils\PluginUtils;
use fernanACM\GiveAll\manager\GiveAllManager;

class SendSubCommand extends BaseSubCommand{

    public function __construct(){
        parent::__construct("send", "", []);
        $this->setPermission(PermissionsUtils::GIVEALL_SEND);
    }

    /**
     * @return void
     */
    protected function prepare(): void{
        $this->registerArgument(0, new IntegerArgument("amount", true));
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
        if(!$sender->hasPermission(PermissionsUtils::GIVEALL_SEND)){
            $sender->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($sender, "Messages.error.no-permission"));
            PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
            return;
        }
        if(!isset($args["amount"])){
            $sender->sendMessage(GiveAll::getPrefix(). TextFormat::colorize("&c/giveall send <amount: 1,2,3...>"));
            PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
            return;
        }
        if(!is_numeric($args["amount"]) && !is_int($args["amount"])){
            $sender->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($sender, "Messages.error.not-numerical"));
            PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
            return;
        }
        GiveAllManager::getInstance()->sendItemsForAll($sender, $args["amount"]);
        PluginUtils::PlaySound($sender, "block.stonecutter.use", 1, 4.4);
    }
}