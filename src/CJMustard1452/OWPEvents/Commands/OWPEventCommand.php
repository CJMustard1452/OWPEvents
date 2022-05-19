<?php

namespace CJMustard1452\OWPEvents\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI\FormAPI;
use pocketmine\event\Listener;
use pocketmine\event\server\CommandEvent;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\Config;


class OWPEventCommand implements Listener{

	/** @var Server */
	public $getServer;

	public $getPlugin;

	public function __construct(){
		$this->getServer = Server::getInstance();
		$this->getPlugin = $this->getServer->getPluginManager()->getPlugin("OWPEvents");
	}

	public function commandEvent(CommandEvent $event){
		$sender = $event->getSender();
		if(explode(" ", $event->getCommand())[0] === "owpevent"){
			if($sender instanceof Player){
			if($sender->hasPermission("owpe-eventscommand.manage")){
				//
				//Ownage Event OP Menu
				$form = $this->getServer->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
					if($data == true){
						//
						//OP Join Event
						if(implode($data) == 0){

							$form = $this->getServer->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
								//
								//Join Code
							});
							$form->setTitle("§l§7(§bOwnage Events§7)");
							$form->addLabel("This feature has not been added yet.");
							$form->sendToPlayer($sender);
						}elseif(implode($data) == 1){
							//
							//OP Host Event
							$form = $this->getServer->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
								if($data == true){
									//
									//Player Hunt
									if(implode($data) == 0){
										$form = $this->getServer->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
											if($data == true){
												if(strlen(implode($data)) >= 1){
													$GameFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . "gamefile.json", Config::JSON);
													if($GameFile->get("EventRunning") == false){
														if($this->getServer->getPlayer(implode($data)) instanceof Player){
															foreach($this->getServer->getOnlinePlayers() as $player){
																$player->sendMessage("§a↣ An event has started, slay§b " . $this->getServer->getPlayer(implode($data))->getName() . "§a to win the game!");
																$GameFile->set("usrhuntplayer", $this->getServer->getPlayer(implode($data))->getName());
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
										$form->setTitle("§l§7(§bOwnage Events§7)");
										$form->addInput("§aTargeted Player");
										$form->sendToPlayer($sender);
										//
										//Top Kills
									}elseif(implode($data) == 1){
										$GameFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . "gamefile.json", Config::JSON);
										if($GameFile->get("EventRunning") == false){
											if(count($this->getServer->getOnlinePlayers()) >= 8){
												foreach($this->getServer->getOnlinePlayers() as $player){
													$NumberOfPlayers = round(count($this->getServer->getOnlinePlayers()) / 3.4);
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
									}
								}

							});
							$form->setTitle("§l§7(§bOwnage Events§7)");
							$form->addStepSlider("§aChoose Option", ["§7Player Hunt", "§7Top Kills"]);
							$form->sendToPlayer($sender);
							//
							//Stop
						}elseif(implode($data) == 2){
							$GameFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . "gamefile.json", Config::JSON);
							if($GameFile->get("EventRunning") == true){
								$GameFile->set("EventRunning", false);
								$GameFile->set("MKActiveGame", false);
								$GameFile->set("usrhuntplayer", null);
								$GameFile->set("UsrHuntActiveGame", false);
								$GameFile->save();
								$this->getServer->broadcastMessage("§a↣ The event has been terminated.");
								foreach($this->getServer->getOnlinePlayers() as $player){
									$Usernames = $player->getName();
									$UserFile = $this->myConfig = new Config($this->getPlugin->getDataFolder() . "$Usernames.json", Config::JSON);
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
				$form->setTitle("§l§7(§bOwnage Events§7)");
				$form->addLabel("§7As an event manager you may create/join events within this menu");
				$form->addDropdown("§7Choose Option", ["§aJoin Event", "§aHost Event", "§aStop Event"]);
				$form->sendToPlayer($sender);

			}else{
				//
				//Ownage Event Player Menu
				$form = $this->getServer->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
					if($data == true){
						//
						//If Event is running --> Join
					}
				});
				$form->setTitle("§l§7(§bOwnage Events§7)");
				$form->addLabel("This feature has not been added yet.");
				$form->sendToPlayer($sender);
				}
			}
		}
	}
}
