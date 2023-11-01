<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
 
namespace fernanACM\GiveAll\task;

use pocketmine\Server;

use pocketmine\scheduler\Task;

use fernanACM\GiveAll\GiveAll;
use fernanACM\GiveAll\manager\GiveAllInventoryManager;
use fernanACM\GiveAll\utils\PluginUtils;

class GiveAllTask extends Task{

    /**
     * @return void
     */
    public function onRun(): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $target){
            $amount = intval(GiveAll::getInstance()->config->getNested("Settings.Assistant.items-to-receive"));
            $inventory = GiveAllInventoryManager::getInstance()->getRandomItems($amount);
            foreach($inventory as $item){
                $target->getInventory()->addItem($item);
            }
        }
        $target->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($target, "Messages.successful.item-received"));
        PluginUtils::PlaySound($target, "random.levelup", 1, 6.5);
    }
}