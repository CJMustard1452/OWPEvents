<?php

namespace CJMustard1452\OWPEvents;

use CJMustard1452\OWPEvents\Listeners\EventListener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use CJMustard1452\OWPEvents\Command\Commands;
use pocketmine\utils\Config;

class OWPEvents extends PluginBase implements Listener{
	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents(new Commands(), $this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}

}