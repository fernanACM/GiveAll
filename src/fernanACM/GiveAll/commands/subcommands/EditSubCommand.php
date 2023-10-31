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

use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;

use fernanACM\GiveAll\GiveAll;
use fernanACM\GiveAll\utils\PermissionsUtils;
use fernanACM\GiveAll\utils\PluginUtils;
use fernanACM\GiveAll\manager\GiveAllInventoryManager;

class EditSubCommand extends BaseSubCommand{

    public function __construct(){
        parent::__construct("edit", "", []);
        $this->setPermission(PermissionsUtils::GIVEALL_EDIT);
    }

    /**
     * @return void
     */
    protected function prepare(): void{
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
        if(!$sender->hasPermission(PermissionsUtils::GIVEALL_EDIT)){
            $sender->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($sender, "Messages.error.no-permission"));
            PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
            return;
        }
        GiveAllInventoryManager::getInstance()->sendEditContent($sender);
        PluginUtils::PlaySound($sender, "block.smoker.smoke", 1, 4.4);
    }
}