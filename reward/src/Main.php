<?php

declare(strict_types=1);

namespace wax_dev\reward;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use wax_dev\reward\Reward\rewardPlayer;

class Main extends PluginBase{


    private Config $config;

    public function onEnable () : void
    {

        $this->getLogger ()->info("plugin active");

        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        $this->getServer()->getCommandMap()->register("reward", new rewardPlayer($this->config));
    }
}
