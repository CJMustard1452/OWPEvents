<?php

namespace CJMustard1452\OWPE_Events;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
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
			##Start ---------------------------------------
			if(strtolower($args[0]) === "start"){
					if($GameFile->get("EventRunning") == false){
						if(isset($args[1])){
							if(strtolower($args[1]) === "mk"){
								if(count($this->getServer()->getOnlinePlayers()) >= 8){
								foreach($this->getServer()->getOnlinePlayers() as $player){
									$NumberOfPlayers = count($this->getServer()->getOnlinePlayers())/2;
									$player->sendTitle('§aan event has started!');
									$player->sendMessage("§d(Events§d) §ba game of §cMass Killer§b Kill $NumberOfPlayers DIFFERENT players to win the game!");
									$GameFile->set("EventRunning", true);
									$GameFile->set("NumberOfPlayers", "$NumberOfPlayers");
									$GameFile->set("MKActiveGame", true);
									$GameFile->save();
								}
								}else{
									$sender->sendMessage("§d(§bStaff Events§d)§a You must have 8+ players online.");
								}
							}elseif(strtolower($args[1]) === "Event2"){
								##Event2 Code
								##--------------------------------------------------------------
							}elseif(strtolower($args[1]) === "Event3"){
								##Event3 Code
								##--------------------------------------------------------------
							}elseif(strtolower($args[1]) === "Event4"){
								##Event4 Code
								##--------------------------------------------------------------
							}elseif(strtolower($args[1]) === "Event5"){
								##Event5 Code
								##--------------------------------------------------------------
							}else{
								$sender->sendMessage("§d(§bStaff Events§d)§a Start | Stop | List");
							}
						}else{
							$sender->sendMessage("§d(§bStaff Events§d)§a Events: Mass Killer: MK, More Events Coming");
						}
					}else{
						$sender->sendMessage("§d(§bStaff Events§d)§a An event is currently running.");
					}
					##List ---------------------------------------
			}elseif(strtolower($args[0]) === "list"){
				$sender->sendMessage("§d(§bStaff Events§d)§a Events: Mass Killer: MK, More Events Coming - CJ is cool");
					##Stop ---------------------------------------
			}elseif(strtolower($args[0]) === "stop"){
				if($GameFile->get("EventRunning") == true){
					$GameFile->set("EventRunning", false);
					$GameFile->set("MKActiveGame", false);
					$GameFile->save();
					$this->getServer()->broadcastMessage("§d(Events§d)§a The event has been terminated.");
					foreach($this->getServer()->getOnlinePlayers() as $player){
						$Usernames = $player->getName();
						$UserFile = $this->myConfig = new Config($this->getDataFolder() . "$Usernames.json", Config::JSON);
						$UserFile->set("Kills", null);
						$UserFile->save();
						for($x = 1; $x <= 50; $x++){
							if($UserFile->get("$x") == true){
								$UserFile->set("$x", null);
								$UserFile->save();
							}
						}
					}
				}else{
					$sender->sendMessage("§d(§bStaff Events§d)§a There are no games running.");
				}
			}else{
				$sender->sendMessage("§d(§bStaff Events§d)§a Start | Stop | List");
			}
		}else{
			$sender->sendMessage("§d(§bStaff Events§d)§a Start | Stop | List");
		}
		return true;
	}
	public function onDeath(PlayerDeathEvent $event){
		if ($event->getPlayer()->getLastDamageCause() instanceof EntityDamageByEntityEvent){
			$UserFile = $this->myConfig = new Config($this->getDataFolder() . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . ".json", Config::JSON);
			$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
			##Mass Killer Event ---------------------------------------
			if($GameFile->get("MKActiveGame") === true){
				for($x = 1; $x <= $GameFile->get("NumberOfPlayers"); $x++){
					if($UserFile->get("$x") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}else{
							if($UserFile->get("Kills") == $GameFile->get("NumberOfPlayers")-1){
								$this->getServer()->broadcastMessage("§b" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " has won the event!");
								foreach($this->getServer()->getOnlinePlayers() as $player){
									$Usernames = $player->getName();
									$UserFile = $this->myConfig = new Config($this->getDataFolder() . "$Usernames.json", Config::JSON);
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
										$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
										$UserFile->save();
										return true;
									}
								}
							}
					}
				}
				##Game2 ---------------------------------------
			}elseif($GameFile->get("MKActiveGame") === true){
				##Game 2 Code
			}
		}
		return true;
	}
	public function onLeave(PlayerQuitEvent $event){
		$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
		$UserFile = $this->myConfig = new Config($this->getDataFolder() . $event->getPlayer()->getName() . ".json", Config::JSON);
		if($GameFile->get("MKActiveGame") == true){
		for($x = 1; $x <= 50; $x++){
			if($UserFile->get("$x") == true){
				$UserFile->set("$x", null);
				$UserFile->set("Kills", null);
				$UserFile->save();
			}
		}
		}
	}
	public function onDisable(){
		$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
		$GameFile->set("MKActiveGame", false);
		$GameFile->set("EventRunning", false);
		$GameFile->save();
		parent::onDisable();
	}
}
