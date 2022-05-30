<?php

namespace CJMustard1452\OWPEvents\Listeners;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;


class EventListener implements Listener{

	/** @var Server */
	public $getServer;

	public $getPlugin;

	public function __construct(){
		$this->getServer = Server::getInstance();
		$this->getPlugin = $this->getServer->getPluginManager()->getPlugin("OWPEvents");
	}

	public function onDeath(PlayerDeathEvent $event){
		if($event->getPlayer()->getLastDamageCause() instanceof EntityDamageByEntityEvent && $event->getPlayer()->getLastDamageCause()->getDamager() instanceof Player && $event->getPlayer() instanceof Player){
			$this->getData = json_decode(file_get_contents($this->getPlugin->getDataFolder() . "imbt"), true);
			if(isset($this->getData["EventCore"]["Current Event"]) && $this->getData["EventCore"]["Current Event"] == "intkills"){
				if(isset($this->getData[$event->getPlayer()->getLastDamageCause()->getDamager()->getName()]["kills"])){
					if($this->getData[$event->getPlayer()->getLastDamageCause()->getDamager()->getName()]["kills"] >= intval($this->getData["EventCore"]["intkills"]) - 1){
						$this->getServer->broadcastMessage("§a↣ §b" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " §ahas killed §b" . $event->getPlayer()->getName() . " §aand won the game!");
						unlink($this->getPlugin->getDataFolder() . "imbt");
						new Config($this->getPlugin->getDataFolder() . "imbt", Config::JSON);
					}
				}else{
					if(!isset($this->getData[$event->getPlayer()->getLastDamageCause()->getDamager()->getName()][$event->getPlayer()->getName()])){
						$this->getData[$event->getPlayer()->getLastDamageCause()->getDamager()->getName()][$event->getPlayer()->getName()] = true;
						if(isset($this->getData[$event->getPlayer()->getLastDamageCause()->getDamager()->getName()]["kills"])){
							$this->getData[$event->getPlayer()->getLastDamageCause()->getDamager()->getName()]["kills"] = $this->getData["kills"] + 1;
						}else{
							$this->getData[$event->getPlayer()->getLastDamageCause()->getDamager()->getName()]["kills"] = 1;
						}
						file_put_contents($this->getPlugin->getDataFolder() . "imbt", json_encode($this->getData));
						$this->getServer->broadcastMessage("§a↣ §b" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . "§a is on §b" . $this->getData[$event->getPlayer()->getLastDamageCause()->getDamager()->getName()]["kills"] . " §akills!");
					}else{
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("§a↣ You have already killed this player.");
					}
				}
			}
		}
	}
}
