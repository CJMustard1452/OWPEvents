<?php

namespace CJMustard1452\Staff_Events;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		$GameFile = $this->myConfig = new Config($this->getDataFolder() ."gamefile.json", Config::JSON);
		if(isset($args[0])){
			if(strtolower($args[0]) === "ev1"){
					if($GameFile->get("EventRunning") == false){
						foreach($this->getServer()->getOnlinePlayers() as $player){
							$player->sendTitle('§aan event has started!');
							$player->sendMessage("§cMass Killer:§b Kill 8 DIFFERENT players to win the game!");
							$GameFile->set("EventRunning", true);
							$GameFile->set("MKActiveGame", true);
							$GameFile->save();
						}
					}else{
						$sender->sendMessage("§d(§bStaff Events§d)§aAn event is currently running!");
					}
			}elseif(strtolower($args[0]) === "event2"){
				##Event 2 Code

			}elseif(strtolower($args[0]) === "stop"){
				if($GameFile->get("EventRunning") == true){
					$GameFile->set("EventRunning", false);
					$GameFile->set("MKActiveGame", false);
					$GameFile->save();
					$this->getServer()->broadcastMessage("§d(§bStaff Events§d)§a Event has been shut down!");
					foreach($this->getServer()->getOnlinePlayers() as $player){
						$Usernames = $player->getName();
						$UserFile = $this->myConfig = new Config($this->getDataFolder() . "$Usernames.json", Config::JSON);
						for($x = 1; $x <= 8; $x++){
							$UserFile->set("$x", null);
							$UserFile->set("Kills", null);
							$UserFile->save();
						}
					}
				}else{
					$sender->sendMessage("§d(§bStaff Events§d)§a There are no active games.");
				}
			}else{
				$sender->sendMessage("§d(§bStaff Events§d)§a Mass Killer EV1 | Event2 | Event3 | stop");
			}
		}else{
			$sender->sendMessage("§d(§bStaff Events§d)§a Mass Killer = EV1 | Event2 | Event3 | stop");
		}
		return true;
	}
	public function onDeath(PlayerDeathEvent $event){
		if ($event->getPlayer()->getLastDamageCause() instanceof EntityDamageByEntityEvent){
			$UserFile = $this->myConfig = new Config($this->getDataFolder() . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . ".json", Config::JSON);
			$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
			if($GameFile->get("MKActiveGame") === true){
				for($x = 1; $x <= 8; $x++){
					if($UserFile->get("$x") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}else{
						if($x >= 8){
							if($UserFile->get("Kills") == "7"){
								$this->getServer()->broadcastMessage("§b" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " has won the event!");
								foreach($this->getServer()->getOnlinePlayers() as $player){
									$Usernames = $player->getName();
									$UserFile = $this->myConfig = new Config($this->getDataFolder() . "$Usernames.json", Config::JSON);
									for($x = 1; $x <= 8; $x++){
										$UserFile->set("$x", null);
										$UserFile->save();
									}
								}
								$GameFile->set("MKActiveGame", false);
								$GameFile->set("EventRunning", false);
								$GameFile->save();
							}else{
								for($x = 1; $x <= 8; $x++){
									if($UserFile->get("$x") == null){
										$UserFile->set("$x", $event->getPlayer()->getName());
										$UserFile->set("Kills", $UserFile->get("Kills") + 1);
										$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
										$UserFile->save();
										return true;
									}
								}
							}
						}
					}
				}
			}
		}
		return true;
	}
	public function onJoin(PlayerLoginEvent $event){
		$UserFile = $this->myConfig = new Config($this->getDataFolder() . $event->getPlayer()->getName() . ".json", Config::JSON);
		for($x = 1; $x <= 8; $x++){
			$UserFile->set("$x", null);
			$UserFile->set("Kills", null);
			$UserFile->save();
		}
	}
	public function onDisable(){
		$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
		$GameFile->set("MKActiveGame", false);
		$GameFile->save();
		parent::onDisable();
	}
}
