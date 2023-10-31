<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM

namespace fernanACM\GiveAll\manager\inventory;

use pocketmine\player\Player;

use muqsit\invmenu\InvMenu;

abstract class InventoryManager{

    /**
     * @return InvMenu
     */
    abstract protected function getInvMenu(): InvMenu;

    /**
     * @param array $content
     * @return void
     */
    abstract protected function setContents(array $content): void;

    /**
     * @return array
     */
    abstract protected function getContents(): array;

    /**
     * @param int $amount
     * @return array
     */
    abstract public function getRandomItems(int $amount): array;

    /**
     * @param Player $player
     * @return void
     */
    abstract public function sendEditContent(Player $player): void;

    /**
     * @return int
     */
    abstract public function getNumContents(): int;

    /**
     * @return void
     */
    abstract public function saveInventory(): void;

    /**
     * @return void
     */
    abstract public function loadInventory(): void;
}