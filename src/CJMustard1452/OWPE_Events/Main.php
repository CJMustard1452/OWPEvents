<?php

namespace CJMustard1452\OWPE_Events;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use jojoe77777\FormAPI\FormAPI;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

		public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		if($this->getServer()->getPlayer($sender->getName()) instanceof Player){
		if($sender->hasPermission("owpe-eventscommand.manage")){
			$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
				$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
					if($data == true){
						if(implode($data) == 0){
							$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
								if($data == true){
									if(strlen(implode($data)) >= 1){
										$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
										if($GameFile->get("EventRunning") == false){
											if($this->getServer()->getPlayer(implode($data)) instanceof Player){
												foreach($this->getServer()->getOnlinePlayers() as $player){
													$player->sendMessage("§a↣ An event has started, slay§b " . $this->getServer()->getPlayer(implode($data))->getName() . "§a to win the game!");
													$GameFile->set("usrhuntplayer", $this->getServer()->getPlayer(implode($data))->getName());
													$GameFile->set("UsrHuntActiveGame", true);
													$GameFile->set("EventRunning", true);
													$GameFile->save();
												}
											}else{
												$sender->sendMessage("§a↣ Please list a a valid player name.");
											}
										}else{
											$sender->sendMessage("§a↣ An event is currently running.");
										}
										}else{
											$sender->sendMessage("§a↣ Please list a player name.");
										}
									}
							});
							//Ownage Player Hunt
							$form->setTitle("§l§7(§bOwnage Events§7)");
							$form->addInput("§aTargeted Player");
							$form->sendToPlayer($sender);
						}elseif(implode($data) == 1){
							$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
							if($GameFile->get("EventRunning") == false){
								if(count($this->getServer()->getOnlinePlayers()) >= 8){
									foreach($this->getServer()->getOnlinePlayers() as $player){
										$NumberOfPlayers = round(count($this->getServer()->getOnlinePlayers()) / 3.4);
										$player->sendMessage("§a↣ an event has started, kill §b$NumberOfPlayers §aDIFFERENT players to win the game!");
										$GameFile->set("EventRunning", true);
										$GameFile->set("NumberOfPlayers", "$NumberOfPlayers");
										$GameFile->set("MKActiveGame", true);
										$GameFile->save();
									}
								}else{
									$sender->sendMessage("§a↣ You must have 8+ players online.");
								}
							}else{
								$sender->sendMessage("§a↣ An event is currently running.");
							}
						}elseif(implode($data) == 2){
							$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
							if($GameFile->get("EventRunning") == true){
								$GameFile->set("EventRunning", false);
								$GameFile->set("MKActiveGame", false);
								$GameFile->set("usrhuntplayer", null);
								$GameFile->set("UsrHuntActiveGame", false);
								$GameFile->save();
								$this->getServer()->broadcastMessage("§a↣ The event has been terminated.");
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
								$sender->sendMessage("§a↣ There are no events running at this time.");
							}
						}
					}
				});
				//Ownage Event Main
				$form->setTitle("§l§7(§bOwnage Events§7)");
				$form->addStepSlider("§aChoices", ["§7Player Hunt", "§7Top Kills", "§7Stop Events"]);
				$form->sendToPlayer($sender);

		}else{
			$sender->sendMessage("§cYou do not have permission to run this command.");
			}
		}else{
			$sender->sendMessage("You can only run this command in game!");
		}
		return true;
	}
	public function onDeath(PlayerDeathEvent $event){
		if($event->getPlayer()->getLastDamageCause() instanceof EntityDamageByEntityEvent){
			$UserFile = $this->myConfig = new Config($this->getDataFolder() . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . ".json", Config::JSON);
			$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
			##Mass Killer Event ---------------------------------------
			if($GameFile->get("MKActiveGame") === true){
				for($x = 1; $x <= $GameFile->get("NumberOfPlayers"); $x++){
					if($UserFile->get("$x") == $event->getPlayer()->getName()){
						$event->getPlayer()->getLastDamageCause()->getDamager()->sendMessage("§a↣ You have already killed this player!");
						return true;
					}else{
						if($UserFile->get("Kills") == $GameFile->get("NumberOfPlayers") - 1){
							$this->getServer()->broadcastMessage("§a↣ " . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " has won the event!");
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
									$this->getServer()->broadcastMessage("§a↣ " . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " is on " . $UserFile->get("Kills") . " kills!");
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
					$this->getServer()->broadcastMessage("§a↣ " . $event->getPlayer()->getLastDamageCause()->getDamager()->getName() . " has killed ".$event->getPlayer()->getName()." and won the game!");
					$GameFile->set("UsrHuntActiveGame", false);
					$GameFile->set("EventRunning", false);
					$GameFile->set("usrhuntplayer", true);
					$GameFile->save();
				}
			}elseif($GameFile->get("MKActiveGame") === true){
				##Game 3 Code
			}elseif($GameFile->get("MKActiveGame") === true){
				##Game 4 Code
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
		}elseif($GameFile->get("UsrHuntActiveGame") == true){
			if($event->getPlayer()->getName() === $GameFile->get("usrhuntplayer")){
				$this->getServer()->broadcastMessage("§a↣ Unfortunately ". $GameFile->get("usrhuntplayer"). " has left the game.");
				$GameFile->set("usrhuntplayer", null);
				$GameFile->set("UsrHuntActiveGame", false);
				$GameFile->set("EventRunning", false);
				$GameFile->save();
			}
		}
	}
	public function onDisable(){
		$GameFile = $this->myConfig = new Config($this->getDataFolder() . "gamefile.json", Config::JSON);
		$GameFile->set("MKActiveGame", false);
		$GameFile->set("EventRunning", false);
		$GameFile->set("usrhuntplayer", null);
		$GameFile->set("UsrHuntActiveGame", false);
		$GameFile->save();
		parent::onDisable();
	}
}
