<?php

declare(strict_types=1);

namespace wax_dev\reward\Reward;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class rewardPlayer extends Command
{
    private Config $rw;

    public function __construct(Config $config)
    {
        parent::__construct("reward", "Permet de récupérer sa récompense quotidienne", "/reward", ["rwd"]);
        $this->setPermission("reward.cmd");
        $this->rw = $config;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if ($sender instanceof Player) {
            $this->rewardLPD($sender);
            return;
        }
        $sender->sendMessage("Cette commande ne peut être utilisée que par un joueur.");
    }

    private function rewardLPD(Player $player): void
    {
        $playerName = $player->getName();
        $time = $this->rw->get($playerName, 0);
        $timeNow = time();

        if ($timeNow - $time >= 86400) { 
            $item1 = ItemFactory::getInstance()->get(278, 0, 1);
            $item2 = ItemFactory::getInstance()->get(285, 0, 1);
            $player->getInventory()->addItem($item1, $item2);
            $this->rw->set($playerName, $timeNow);
            $this->rw->save();
            $player->sendMessage('§a§lVous avez bien récupéré votre récompense du jour !');
        } else {
            $remainingTime = 86400 - ($timeNow - $time);
            $hourMinuteSecond = gmdate("H:i:s", $remainingTime);
            $timeParts = explode(":", $hourMinuteSecond);
            $player->sendMessage("§4§lIl faut encore attendre {$timeParts[0]} heure/s, {$timeParts[1]} minute/s et {$timeParts[2]} seconde/s avant de récupérer ta prochaine récompense.");
        }
    }
}
