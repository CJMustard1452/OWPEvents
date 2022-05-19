<?php

namespace CJMustard1452\OWPEvents;

use CJMustard1452\OWPEvents\Listeners\EventListener;
use CJMustard1452\OWPEvents\Commands\OWPEventCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class OWPEvents extends PluginBase implements Listener{
	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents(new OWPEventCommand(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
		$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
		$GameFile->set("MKActiveGame", false);
		$GameFile->set("EventRunning", false);
		$GameFile->set("usrhuntplayer", null);
		$GameFile->set("UsrHuntActiveGame", false);
		$GameFile->save();
	}

}