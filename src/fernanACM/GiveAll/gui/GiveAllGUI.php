<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
 
namespace fernanACM\GiveAll\gui;

use Closure;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\utils\TextFormat;

use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;

use pocketmine\inventory\Inventory;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;

use fernanACM\GiveAll\GiveAll;
use fernanACM\GiveAll\manager\GiveAllManager;
use fernanACM\GiveAll\utils\PluginUtils;

class GiveAllGUI{

    /** @var GiveAllGUI|null $instance */
    private static ?GiveAllGUI $instance = null;

    private function __construct(){
    }

    /**
     * @param Player $player
     * @return void
     */
    public function getGiveAllGUI(Player $player): void{
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $inv = $menu->getInventory();
        # Inventory
        $menu->setName(TextFormat::colorize("&l&2GIVEALL"));
        for($i = 0; $i < 27; $i++){
            if(!in_array($i, [2, 11, 13, 15])){
                $inv->setItem($i, VanillaBlocks::IRON_BARS()->asItem()->setCustomName(TextFormat::colorize("&r")));
            }
        }
        $inv->setItem(2, VanillaBlocks::IRON_BARS()->asItem()->setCustomName(GiveAll::getMessage($player, "Menu.GUI.Items.place-item")));
        $inv->setItem(13, VanillaBlocks::BARRIER()->asItem()->setCustomName(GiveAll::getMessage($player, "Menu.GUI.Items.close-menu")));
        $inv->setItem(15, VanillaBlocks::EMERALD()->asItem()->setCustomName(GiveAll::getMessage($player, "Menu.GUI.Items.item-send")));
        $menu->setListener(Closure::fromCallable([$this, "listernerGiveAll"]));
        $menu->setInventoryCloseListener(Closure::fromCallable([$this, "inventoryCloseListener"]));
        $menu->send($player);
    }

    /**
     * @param InvMenuTransaction $transaction
     * @return InvMenuTransactionResult
     */
    private function listernerGiveAll(InvMenuTransaction $transaction): InvMenuTransactionResult{
        $player = $transaction->getPlayer();
        $slot = $transaction->getAction()->getSlot();
        switch($slot){
            case 11: // place item
            return $transaction->continue();

            case 13: // close menu
                $player->removeCurrentWindow();
                PluginUtils::PlaySound($player, "block.stonecutter.use", 1, 3.4);
            break;

            case 15: // submit item
                $item = $transaction->getAction()->getInventory()->getItem(11);
                if(!$item->equals(VanillaItems::AIR())){
                    foreach(Server::getInstance()->getOnlinePlayers() as $target){
                        GiveAllManager::getInstance()->giveItemForAll($item, $target);
                    }
                    $itemData = str_replace(["{ITEM_NAME}", "{ITEM_COUNT}"], [$item->getName(), $item->getCount()], 
                    GiveAll::getMessage($player, "Messages.successful.item-sent"));
                    $player->sendMessage(GiveAll::getPrefix(). $itemData);
                }else{
                    $player->removeCurrentWindow();
                    $player->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($player, "Messages.error.no-item"));
                    PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                }
            break;
        }
        return $transaction->discard();
    }

    /**
     * @param Player $player
     * @param Inventory $inventory
     * @return void
     */
    private function inventoryCloseListener(Player $player, Inventory $inventory): void{
        $item = $inventory->getItem(11);
        if(!$player->getInventory()->canAddItem($item)){
            $player->getWorld()->dropItem($player->getPosition(), $item);
        }else $player->getInventory()->addItem($item);
    }

    /**
     * @return self
     */
    public static function getInstance(): self{
        if(is_null(self::$instance)) self::$instance = new self();
        return self::$instance;
    }
}