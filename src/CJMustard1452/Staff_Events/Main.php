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
			if($args[0] === "EV1"){
				if($GameFile->get("GameRan") == false){
					if($GameFile->get("EventRunning") == false){
						foreach($this->getServer()->getOnlinePlayers() as $player){
							$player->sendTitle('§aan event has started!');
							$player->sendMessage("§cMass Killer:§b Kill 8 DIFFERENT players to win the game!");
							$GameFile->set("EventRunning", true);
							$GameFile->set("GameRan", true);
							$GameFile->set("MKActiveGame", true);
							$GameFile->save();
						}
					}
				}else{
					$sender->sendMessage("§d(§bStaff Events§d)§a You must restart the server to run another game!");
				}
			}elseif($args[0] === "event2"){
				##Event 2 Code

			}elseif($args[0] === "stop"){
				if($GameFile->get("EventRunning") == true){
					$GameFile->set("EventRunning", false);
					$GameFile->set("MKActiveGame", false);
					$GameFile->save();
					$sender->sendMessage("§d(§bStaff Events§d)§a You must restart the server to run another game!");
					$this->getServer()->broadcastMessage("§d(§bStaff Events§d)§a Event has been shut down!");
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
			$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
			if($GameFile->get("MKActiveGame") === true){
				if($event->getPlayer()->getLastDamageCause()->getDamager() instanceof Player){
					$UserFile = $this->myConfig = new Config($this->getDataFolder() . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . ".json", Config::JSON);
					if($UserFile->get("1") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}elseif($UserFile->get("2") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}elseif($UserFile->get("3") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}elseif($UserFile->get("4") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}elseif($UserFile->get("5") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}elseif($UserFile->get("6") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}elseif($UserFile->get("7") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}elseif($UserFile->get("8") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("You have already killed this player!");
						return true;
					}else{
						if($UserFile->get("Kills") == "7"){
							$this->getServer()->broadcastMessage("§b" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . "has won the event!");
							$GameFile->set("MKActiveGame", false);
							$GameFile->save();
						}else{
							if($UserFile->get("1") == null){
								$UserFile->set("1", $event->getPlayer()->getName());
								$UserFile->set("Kills", $UserFile->get("Kills") + 1);
								$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
								$UserFile->save();
							}elseif($UserFile->get("2") == null){
								$UserFile->set("2", $event->getPlayer()->getName());
								$UserFile->set("Kills", $UserFile->get("Kills") + 1);
								$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
								$UserFile->save();
							}elseif($UserFile->get("3") == null){
								$UserFile->set("3", $event->getPlayer()->getName());
								$UserFile->set("Kills", $UserFile->get("Kills") + 1);
								$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
								$UserFile->save();
							}elseif($UserFile->get("4") == null){
								$UserFile->set("4", $event->getPlayer()->getName());
								$UserFile->set("Kills", $UserFile->get("Kills") + 1);
								$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
								$UserFile->save();
							}elseif($UserFile->get("5") == null){
								$UserFile->set("5", $event->getPlayer()->getName());
								$UserFile->set("Kills", $UserFile->get("Kills") + 1);
								$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
								$UserFile->save();
							}elseif($UserFile->get("6") == null){
								$UserFile->set("6", $event->getPlayer()->getName());
								$UserFile->set("Kills", $UserFile->get("Kills") + 1);
								$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
								$UserFile->save();
							}elseif($UserFile->get("7") == null){
								$UserFile->set("7", $event->getPlayer()->getName());
								$UserFile->set("Kills", $UserFile->get("Kills") + 1);
								$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
								$UserFile->save();
							}elseif($UserFile->get("8") == null){
								$UserFile->set("8", $event->getPlayer()->getName());
								$UserFile->set("Kills", $UserFile->get("Kills") + 1);
								$this->getServer()->broadcastMessage("§d" . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
								$UserFile->save();
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
		$UserFile->set("1", null);
		$UserFile->set("2", null);
		$UserFile->set("3", null);
		$UserFile->set("4", null);
		$UserFile->set("5", null);
		$UserFile->set("6", null);
		$UserFile->set("7", null);
		$UserFile->set("8", null);
		$UserFile->set("Kills", null);
		$UserFile->save();
	}
	public function onDisable(){
		$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
		$GameFile->set("MKActiveGame", false);
		$GameFile->set("GameRan", false);
		$GameFile->save();
		parent::onDisable();
	}
}
