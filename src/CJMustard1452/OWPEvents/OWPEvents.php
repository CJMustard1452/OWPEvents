<?php

namespace CJMustard1452\OWPEvents;

use CJMustard1452\OWPEvents\Listeners\EventListener;
use CJMustard1452\OWPEvents\Commands\OWPEventCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class OWPEvents extends PluginBase implements Listener{
	public function onEnable(): void{
		unlink($this->getDataFolder() . "imbt");
		new Config($this->getDataFolder() . "imbt", Config::JSON);
		$this->getServer()->getPluginManager()->registerEvents(new OWPEventCommand(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}
	public function onDisable() :Void{
		unlink($this->getDataFolder() . "imbt");
	}
}