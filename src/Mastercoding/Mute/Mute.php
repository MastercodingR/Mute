<?php
/**
 * Created by PhpStorm.
 * User: Mastercoding
 * Date: 26.12.2018
 * Time: 11:33
 */
namespace Mastercoding\Mute;

use Mastercoding\Mute\Commands\MuteCommand;
use Mastercoding\Mute\Commands\UnMuteCommand;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class Mute extends PluginBase implements Listener {

    public const Prefix = "§4Mute§7│";

    public static $instance;

    public function onEnable()
    {
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      $this->getLogger()->info("§aMute wird Geladen (Mastercoding)");

      self::$instance = $this;

      Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("unmute"));

      $this->registerCommands();
      $this->registerEvents();

    }

    private function registerCommands(){
        $map = Server::getInstance()->getCommandMap();
        $map->register("mute", new MuteCommand($this, "mute", "Mute Spieler"));
        $map->register("unmute", new UnMuteCommand($this, "unmute", "Unmute Spieler"));
    }

    private function registerEvents(){
        $plugin = Server::getInstance()->getPluginManager();
        $plugin->registerEvents(new EventListener($this),$this);
    }

    public function onConfig() : Config {
        $config = new Config($this->getDataFolder() . "mutes.json", Config::JSON);
        $config->reload();
        return $config;
    }


    public function onPreLogin(PlayerPreLoginEvent $ev){
        $player = $ev->getPlayer();
        $name = $player->getName();

        $config = $this->onConfig();

        if (!$config->exists($name)){
            $config->set($name, array("Mute"=>false,"Grund"=>"~","Von"=>"~"));
            $config->save();
            $this->getLogger()->info(self::Prefix . "§6User Registriert: §2{$name}");
        }
    }

    public function setMute(string $name, string $grund, string $von){
        $config = $this->onConfig();
        $config->setNested($name . ".Mute", true);
        $config->setNested($name . ".Grund", $grund);
        $config->setNested($name . ".Von", $von);
        $config->save();
    }

    public function unMute(string $name){
        $config = $this->onConfig();
        $config->setNested($name . ".Mute",false);
        $config->setNested($name . ".Grund","~");
        $config->setNested($name . ".Von","~");
        $config->save();
    }

    public function sendMuteList(Player $player){
        $config = $this->onConfig();
        $player->sendMessage("§7---- §6MuteList §7----");
        foreach ($config->getAll() as $names => $info){
            if ($info["Mute"] === true){
                $player->sendMessage("§7$names §8- §7Grund §6{$info["Grund"]} §7Von §6{$info["Von"]}");
            }
        }
    }

    public static function getInstance() : Mute {
        return self::$instance;
    }
}