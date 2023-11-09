<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
 
namespace fernanACM\GiveAll\forms;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\utils\TextFormat;

use Vecnavium\FormsUI\ModalForm;

use fernanACM\GiveAll\GiveAll;
use fernanACM\GiveAll\manager\GiveAllManager;
use fernanACM\GiveAll\utils\PluginUtils;

class GiveAllForm{

    /** @var GiveAllForm|null $instance */
    private static ?GiveAllForm $instance = null;

    private function __construct(){
    }

    /**
     * @param Player $player
     * @return void
     */
    public function getGiveAllForm(Player $player): void{
        $modal = new ModalForm(function(Player $player, $data){
            if(is_null($data)){
                PluginUtils::PlaySound($player, "block.stonecutter.use", 1, 3.4);
                return true;
            }
            switch($data){
                case true:
                    $item = $player->getInventory()->getItemInHand();
                    foreach(Server::getInstance()->getOnlinePlayers() as $target){
                        GiveAllManager::getInstance()->giveItemInHand($player, $target, false);
                    }
                    $itemData = str_replace(["{ITEM_NAME}", "{ITEM_COUNT}"], [$item->getName(), $item->getCount()], 
                    GiveAll::getMessage($player, "Messages.successful.item-sent"));
                    $player->sendMessage(GiveAll::getPrefix(). $itemData);
                    $target->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($target, "Messages.successful.item-received"));
                    PluginUtils::PlaySound($player, "random.pop2", 1, 6.5);
                    PluginUtils::PlaySound($target, "random.levelup", 1, 6.5);
                break;

                case false:
                    PluginUtils::PlaySound($player, "block.stonecutter.use", 1, 3.4);
                break;
            }
        });
        # vars
        $item = $player->getInventory()->getItemInHand();
        $itemName = $item->getName();
        $itemCount = $item->getCount();
        $content = GiveAll::getMessage($player, "Menu.Form.content");
        $content = str_replace(["{ITEM_NAME}", "{ITEM_COUNT}"], [$itemName, $itemCount], $content);
        if($item->isNull()){
            $player->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($player, "Messages.error.no-item"));
            PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
            return;
        }
        # FORM
        $modal->setTitle(TextFormat::colorize("&l&2GIVEALL"));
        $modal->setContent($content);
        $modal->setButton1(GiveAll::getMessage($player, "Menu.Form.accept-button"));
        $modal->setButton2(GiveAll::getMessage($player, "Menu.Form.deny-button"));
        $player->sendForm($modal);
    }

    /**
     * @return self
     */
    public static function getInstance(): self{
        if(is_null(self::$instance)) self::$instance = new self();
        return self::$instance;
    }
}