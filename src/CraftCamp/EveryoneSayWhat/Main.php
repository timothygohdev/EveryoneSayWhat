<?php

namespace CraftCamp\EveryoneSayWhat;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase {

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$this->checkCommand($sender, $args)) {
            return true;
        }
        $message = implode(" ", $args);
        $count = 0;
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            if ($player === $sender || $player->hasPermission("everyonesaywhat.deny")) {
                continue;
            }
            $player->chat($message);
            $count++;
        }
        if ($count === 0) {
            $sender->sendMessage("No players to execute command on");
        } else {
            $this->logCommand($sender, $message);
        }
        return true;
    }

    private function checkCommand(CommandSender $sender, array $args): bool {
        if (empty($args)) {
            $sender->sendMessage("Please provide a message to be broadcasted");
            return false;
        }
        if (empty($this->getServer()->getOnlinePlayers())) {
            $sender->sendMessage("No players to execute command on");
            return false;
        }
        return true;
    }

    private function logCommand(CommandSender $sender, string $message): void {
        $name = $sender instanceof Player ? $sender->getName() : "Console";
        $this->getServer()->getLogger()->info("[$name] ESW: $message");
    }
}