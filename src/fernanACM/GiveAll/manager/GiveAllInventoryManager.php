<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
 
namespace fernanACM\GiveAll\manager;

use pocketmine\player\Player;

use pocketmine\utils\Config;

use pocketmine\inventory\Inventory;

use muqsit\invmenu\InvMenu;

use fernanACM\GiveAll\GiveAll;
use fernanACM\GiveAll\utils\ItemUtils;
use fernanACM\GiveAll\utils\PluginUtils;
use fernanACM\GiveAll\manager\inventory\InventoryManager;

class GiveAllInventoryManager extends InventoryManager{

    /** @var GiveAllInventoryManager|null $instance */
    private static ?GiveAllInventoryManager $instance = null;

    private const JSON = "backup/rollback-inv.json";

    /** @var array $menu */
    private array $menu = [];

    private function __construct(){
    }

    /**
     * @return InvMenu
     */
    protected function getInvMenu(): InvMenu{
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $menu->setName("GiveAll Inventory");
        $menu->getInventory()->setContents($this->getContents());
        return $menu;
    }

    /**
     * @param array $content
     * @return void
     */
    protected function setContents(array $content): void{
        self::$menu = $content;
    }

    /**
     * @return array
     */
    protected function getContents(): array{
        $menu = [];
        foreach(self::$menu as $content => $item){
            $menu[$content] = $item;
        }
        return $menu;
    }

    /**
     * @return int
     */
    public function getNumContents(): int{
        return count(self::$menu);
    }

    /**
     * @param int $amount
     * @return array
     */
    public function getRandomItems(int $amount): array{
        $menu = $this->getContents();
        $items = [];
        if(empty($menu)){
            return $items;
        }
        for($i = 0; $i < $amount; $i++){
            $items[] = $menu[array_rand($menu)];
        }
        return $items;
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendEditContent(Player $player): void{
        $menu = $this->getInvMenu();
        $menu->setInventoryCloseListener(function(Player $player, Inventory $inventory): void{
            $content = [];
            foreach($inventory->getContents() as $index => $item){
                $content[$index] = $item;
            }
            $this->setContents($content);
            // backup
            $this->saveInventory();
            $player->sendMessage(GiveAll::getPrefix(). GiveAll::getMessage($player, "Messages.successful.inventory-saved-successfully"));
            PluginUtils::PlaySound($player, "block.stonecutter.use", 1, 2.1);
        });
        $menu->send($player);
    }

    /**
     * @return void
     */
    public function saveInventory(): void{
        $backup = new Config(GiveAll::getInstance()->getDataFolder(). self::JSON);
        $menu = $this->getContents();
        $place = [];
        foreach($menu as $content => $item){
            $place[$content]["slot"] = $content;
            $place[$content]["item"] = ItemUtils::encodeItem($item);
        }
        $backup->setAll($place);
        $backup->save();
    }

    /**
     * @return void
     */
    public function loadInventory(): void{
        $inv = new Config(GiveAll::getInstance()->getDataFolder(). self::JSON);
        $contents = [];
        foreach($inv->getAll() as $content){
            $item = ItemUtils::decodeItem($content["item"]);
            $contents[$content["slot"]] = $item;
        }
        $this->setContents($contents);
    }

    /**
     * @return self
     */
    public static function getInstance(): self{
        if(is_null(self::$instance)) self::$instance = new self();
        return self::$instance;
    }
}