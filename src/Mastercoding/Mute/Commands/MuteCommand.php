<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.12.2018
 * Time: 11:41
 */

namespace Mastercoding\Mute\Commands;

use Mastercoding\Mute\Mute;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Server;

class MuteCommand extends Command implements Listener {
    protected $pl;

    public function __construct(Mute $pl, string $name, string $description = "", string $usageMessage = null, $aliases = [], array $overloads = null)
    {
        $this->pl = $pl;
        parent::__construct($name, $description, $usageMessage, $aliases, $overloads);
        $this->setPermission("mute.perm");
    }


    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($this->testPermission($sender)){
            if (empty($args[0]) and empty($args[1])) {
                $sender->sendMessage(Mute::Prefix . "§7---- §3MuteCommands §7----\n§b/mute §6name §aGrund\n§b/mute §6list\n§b/unmute §6name");
            }elseif ($args[0] === "list"){
                Mute::getInstance()->sendMuteList($sender);
            }else{
                $config = Mute::getInstance()->onConfig();
                $player = Server::getInstance()->getPlayer($args[0]);
                $offlineplayer = $args[0];
                unset($args[0]);
                $grund = implode(" ", $args);
                if ($player !== NULL) {
                    Mute::getInstance()->setMute($player->getName(), $grund, $sender->getName());
                    $sender->sendMessage(Mute::Prefix . "§2Du hast den Spieler §6{$player->getName()} §2für §6{$grund} §2gemutet");
                    $player->sendMessage(Mute::Prefix . "§4Du wurdest für §6{$grund} §4von §6{$sender->getName()} §4gemutet");
                }else{
                    if ($config->exists($offlineplayer)){
                        Mute::getInstance()->setMute($offlineplayer, $grund, $sender->getName());
                        $sender->sendMessage(Mute::Prefix . "§2Du hast den Spieler §6{$offlineplayer} §2für §6{$grund} §2gemutet");
                    }else{
                        $sender->sendMessage(Mute::Prefix . "§4Diesen Spieler gibt es nicht!");
                    }
                }
            }
        }
    }
}