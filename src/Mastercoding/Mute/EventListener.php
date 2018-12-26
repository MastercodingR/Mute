<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.12.2018
 * Time: 11:53
 */

namespace Mastercoding\Mute;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\MainLogger;

class EventListener implements Listener {
    protected $pl;

    public function __construct(Mute $pl)
    {
        $this->pl = $pl;
    }

    public function onChat(PlayerChatEvent $ev){
        $player = $ev->getPlayer();
        $name = $player->getName();

        if (!$ev->isCancelled()){
            var_dump("MUTE");
            $config = Mute::getInstance()->onConfig();
            $info = $config->get($name);
            if ($info["Mute"] === true){
                $player->sendMessage(Mute::Prefix . "§4Du bist Gemutet Grund §6{$info["Grund"]} §4von §6{$info["Von"]}");
                $ev->setCancelled();
            }
        }
    }
}