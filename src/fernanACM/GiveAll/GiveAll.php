<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
 
namespace fernanACM\GiveAll;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use pocketmine\plugin\PluginBase;
# LIbs
use Vecnavium\FormsUI\FormsUI;

use muqsit\simplepackethandler\SimplePacketHandler;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\InvMenu;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\PacketHooker;

use DaPigGuy\libPiggyUpdateChecker\libPiggyUpdateChecker;
# My files
use fernanACM\GiveAll\forms\GiveAllForm;
use fernanACM\GiveAll\gui\GiveAllGUI;
use fernanACM\GiveAll\task\GiveAllTask;
use fernanACM\GiveAll\utils\PluginUtils;
use fernanACM\GiveAll\commands\GiveAllCommand;
use fernanACM\GiveAll\manager\GiveAllInventoryManager;
use fernanACM\GiveAll\manager\GiveAllManager;

class GiveAll extends PluginBase{

    /** @var Config $config */
    public Config $config;

    /** @var GiveAll $instance */
    private static GiveAll $instance;

    # CheckConfig
    private const CONFIG_VERSION = "1.0.0";
 
    /**
     * @return void
     */
    public function onLoad(): void{
        self::$instance = $this;
        $this->loadFiles();
    }

    /**
     * @return void
     */
    public function onEnable(): void{
        $this->loadCheck();
        $this->loadVirions();
        $this->loadCommands();
        $this->loadTasks();
        GiveAllInventoryManager::getInstance()->loadInventory();
    }

    /**
     * @return void
     */
    public function onDisable(): void{
        GiveAllInventoryManager::getInstance()->saveInventory();
    }

    /**
     * @return void
     */
    private function loadFiles(): void{
        @mkdir($this->getDataFolder(). "backup");
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml");
    }

    /**
     * @return void
     */
    private function loadCheck(): void{
        # CONFIG
        if((!$this->config->exists("config-version")) || ($this->config->get("config-version") != self::CONFIG_VERSION)){
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_old.yml");
            $this->saveResource("config.yml");
            $this->getLogger()->critical("Your configuration file is outdated.");
            $this->getLogger()->notice("Your old configuration has been saved as config_old.yml and a new configuration file has been generated. Please update accordingly.");
        }
    }

    /**
     * @return void
     */
    private function loadVirions(): void{
        foreach([
            "FormsUI" => FormsUI::class,
            "SimplePacketHandler" => SimplePacketHandler::class,
            "Commando" => BaseCommand::class,
            "InvMenu" => InvMenu::class,
            "libPiggyUpdateChecker" => libPiggyUpdateChecker::class
            ] as $virion => $class
        ){
            if(!class_exists($class)){
                $this->getLogger()->error($virion . " virion not found. Please download GiveAll from Poggit-CI or use DEVirion (not recommended).");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                return;
            }
        }
        if(!PacketHooker::isRegistered()){
            PacketHooker::register($this);
        }
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }
        # Update
        libPiggyUpdateChecker::init($this);
    }

    /**
     * @return void
     */
    private function loadCommands(): void{
        Server::getInstance()->getCommandMap()->register("giveall", new GiveAllCommand);
    }

    /**
     * @return void
     */
    private function loadTasks(): void{
        if($this->config->getNested("Settings.Assistant.enabled")){
            $this->getScheduler()->scheduleDelayedTask(new GiveAllTask, $this->config->get("Settings.Assistant.delay") * 20);
        }
    }

    /**
     * @return GiveAllForm
     */
    public function getGiveAllForm(): GiveAllForm{
        return GiveAllForm::getInstance();
    }

    /**
     * @return GiveAllGUI
     */
    public function getGiveAllGUI(): GiveAllGUI{
        return GiveAllGUI::getInstance();
    }

    /**
     * @return GiveAllManager
     */
    public function getGiveAllManager(): GiveAllManager{
        return GiveAllManager::getInstance();
    }

    /**
     * @return GiveAllInventoryManager
     */
    public function getGiveAllInventory(): GiveAllInventoryManager{
        return GiveAllInventoryManager::getInstance();
    }

    /**
     * @return GiveAll
     */
    public static function getInstance(): GiveAll{
        return self::$instance;
    }

    /**
     * @param Player $player
     * @param string $key
     * @return string
     */
    public static function getMessage(Player $player, string $key): string{
        $messageArray = self::$instance->config->getNested($key, []);
        if(!is_array($messageArray)){
            $messageArray = [$messageArray];
        }
        $message = implode("\n", $messageArray);
        return PluginUtils::codeUtil($player, $message);
    }

    /**
     * @return string
     */
    public static function getPrefix(): string{
        return TextFormat::colorize(self::$instance->config->get("Prefix"));
    }
}