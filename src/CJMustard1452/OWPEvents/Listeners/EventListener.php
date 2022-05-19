<?php

namespace CJMustard1452\OWPEvents\Listeners;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
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
		if($event->getPlayer()->getLastDamageCause() instanceof EntityDamageByEntityEvent){
			$UserFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . ".json", Config::JSON);
			$GameFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . "gamefile.json", Config::JSON);
			##Mass Killer Event ---------------------------------------
			if($GameFile->get("MKActiveGame") === true){
				for($x = 1; $x <= $GameFile->get("NumberOfPlayers"); $x++){
					if($UserFile->get("$x") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("§a↣ You have already killed this player.");
						return true;
					}else{
						if($UserFile->get("Kills") == $GameFile->get("NumberOfPlayers") - 1){
							$this->getServer->broadcastMessage("§a↣ §b" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " §ahas won the event!");
							foreach($this->getServer->getOnlinePlayers() as $player){
								$Usernames = $player->getName();
								$UserFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . "$Usernames.json", Config::JSON);
								for($x = 1; $x <= 50; $x++){
									if($UserFile->get("$x") == true){
										$UserFile->set("Kills", null);
										$UserFile->set("$x", null);
										$UserFile->save();
									}
								}
							}
							$GameFile->set("MKActiveGame", false);
							$GameFile->set("EventRunning", false);
							$GameFile->save();
						}else{
							for($x = 1; $x <= $GameFile->get("NumberOfPlayers"); $x++){
								if($UserFile->get("$x") == null){
									$UserFile->set("$x", $event->getPlayer()->getName());
									$UserFile->set("Kills", $UserFile->get("Kills") + 1);
									$this->getServer->broadcastMessage("§a↣ §b" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . "§a is on §b" . $UserFile->get("Kills") . " §akills!");
									$UserFile->save();
									return true;
								}
							}
						}
					}
				}
				##Game2 ---------------------------------------
			}elseif($GameFile->get("UsrHuntActiveGame") === true){
				if($event->getPlayer()->getName() === $GameFile->get("usrhuntplayer")){
					$Killer = $event->getPlayer()->getLastDamageCause()->getDamager();
					$Killed = $event->getPlayer()->getName();
					$this->getServer->broadcastMessage("§a↣ §b" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " §ahas killed §b" . $event->getPlayer()->getName() . " §aand won the game!");
					$GameFile->set("UsrHuntActiveGame", false);
					$GameFile->set("EventRunning", false);
					$GameFile->set("usrhuntplayer", true);
					$GameFile->save();
				}
			}else{
				return true;
			}
		}
		return true;
	}
	public function onLeave(PlayerQuitEvent $event){
		$GameFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . "gamefile.json", Config::JSON);
		$UserFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . $event->getPlayer()->getName() . ".json", Config::JSON);
		if($GameFile->get("MKActiveGame") == true){
			for($x = 1; $x <= 50; $x++){
				if($UserFile->get("$x") == true){
					$UserFile->set("$x", null);
					$UserFile->set("Kills", null);
					$UserFile->save();
				}
			}
		}elseif($GameFile->get("UsrHuntActiveGame") == true){
			if($event->getPlayer()->getName() === $GameFile->get("usrhuntplayer")){
				$this->getServer->broadcastMessage("§a↣ Unfortunately §b". $GameFile->get("usrhuntplayer"). " §has left the game.");
				$GameFile->set("usrhuntplayer", null);
				$GameFile->set("UsrHuntActiveGame", false);
				$GameFile->set("EventRunning", false);
				$GameFile->save();
			}
		}
	}
	public function onDisable() :Void{
		$GameFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . "gamefile.json", Config::JSON);
		$GameFile->set("MKActiveGame", false);
		$GameFile->set("EventRunning", false);
		$GameFile->set("usrhuntplayer", null);
		$GameFile->set("UsrHuntActiveGame", false);
		$GameFile->save();
	}


}
