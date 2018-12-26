<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.12.2018
 * Time: 12:06
 */
namespace Mastercoding\Mute\Commands;

use Mastercoding\Mute\Mute;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class UnMuteCommand extends Command
{
    protected $pl;

    public function __construct(Mute $pl, string $name, string $description = "", string $usageMessage = null, $aliases = [], array $overloads = null)
    {
        $this->pl = $pl;
        parent::__construct($name, $description, $usageMessage, $aliases, $overloads);
        $this->setPermission("mute.perm");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($this->testPermission($sender)) {
            if (empty($args[0])) {
                $sender->sendMessage(Mute::Prefix . "§b/unmute §6name");
            } else {
                $config = Mute::getInstance()->onConfig();
                $player = Server::getInstance()->getPlayer($args[0]);
                if ($player !== NULL) {
                    if ($config->exists($player->getName())) {
                        $info = $config->get($player->getName());
                        if ($info["Mute"] === true) {
                            Mute::getInstance()->unMute($player->getName());
                            $sender->sendMessage(Mute::Prefix . "§2Der Spieler §6{$player->getName()} §2wurde unmutet");
                            $player->sendMessage(Mute::Prefix . "§2Du wurdest von §6{$sender->getName()} §2unmutet");
                        } else {
                            $sender->sendMessage(Mute::Prefix . "§4Der Spieler ist nicht Gemutet");
                        }
                    } else {
                        $sender->sendMessage(Mute::Prefix . "§4Diesen Spieler gibt es nicht!");
                    }
                }else{
                    if ($config->exists($args[0])) {
                        $info = $config->get($args[0]);
                        if ($info["Mute"] === true) {
                            Mute::getInstance()->unMute($args[0]);
                            $sender->sendMessage(Mute::Prefix . "§2Der Spieler §6{$args[0]} §2wurde unmutet");
                        } else {
                            $sender->sendMessage(Mute::Prefix . "§4Der Spieler ist nicht Gemutet");
                        }
                    } else {
                        $sender->sendMessage(Mute::Prefix . "§4Diesen Spieler gibt es nicht!");
                    }
                }
            }

        }
    }
}