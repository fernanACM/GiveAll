<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
 
namespace fernanACM\GiveAll\manager;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\item\Item;

use fernanACM\GiveAll\GiveAll;
use fernanACM\GiveAll\utils\PluginUtils;

class GiveAllManager{

    /** @var GiveAllManager|null $instance */
    private static ?GiveAllManager $instance = null;

    private function __construct(){
    }

    /**
     * @param Player $player
     * @param Player $target
     * @return void
     */
    public function giveItemInHand(Player $player, Player $target): void{
        $item = $player->getInventory()->getItemInHand();
        $target->getInventory()->addItem($item);
        $itemData = str_replace(["{ITEM_NAME}", "{ITEM_COUNT}"], [$item->getName(), $item->getCount()], 
        GiveAll::getMessage($player, "Messages.successful.item-sent"));
        $player->sendMessage(GiveAll::getPrefix(). $itemData);
        $target->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($target, "Messages.successful.item-received"));
        PluginUtils::PlaySound($player, "random.pop2", 1, 6.5);
        PluginUtils::PlaySound($target, "random.levelup", 1, 6.5);
    }

    /**
     * @param Item $item
     * @param Player $target
     * @return void
     */
    public function giveItemForAll(Item $item, Player $target): void{
        $target->getInventory()->addItem($item);
        $target->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($target, "Messages.successful.item-received"));
        PluginUtils::PlaySound($target, "random.levelup", 1, 6.5);
    }

    /**
     * @param Player $player
     * @param integer $amount
     * @return void
     */
    public function sendItemsForAll(Player $player, int $amount): void{
        $inventory = GiveAllInventoryManager::getInstance()->getRandomItems($amount);
        foreach($inventory as $item){
            foreach(Server::getInstance()->getOnlinePlayers() as $target){
                $target->getInventory()->addItem($item);
            }
        }
        $player->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($player, "Messages.successful.item-sent-auto"));
        $target->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($target, "Messages.successful.item-received"));
        PluginUtils::PlaySound($player, "random.pop2", 1, 6.5);
        PluginUtils::PlaySound($target, "random.levelup", 1, 6.5);
    }

    /**
     * @return self
     */
    public static function getInstance(): self{
        if(is_null(self::$instance)) self::$instance = new self();
        return self::$instance;
    }
}