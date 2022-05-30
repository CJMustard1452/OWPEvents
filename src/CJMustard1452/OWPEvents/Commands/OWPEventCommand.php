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

	public $getData;

	public function __construct(){
		$this->getServer = Server::getInstance();
		$this->getPlugin = $this->getServer->getPluginManager()->getPlugin("OWPEvents");
	}

	public function commandEvent(CommandEvent $event){
		$sender = $event->getSender();
		if(explode(" ", $event->getCommand())[0] === "owpevent"){
			if($sender instanceof Player){
			if($sender->hasPermission("owpe-eventscommand.manage")){
				$this->getData = json_decode(file_get_contents($this->getPlugin->getDataFolder() . "imbt"), true);
				$form = $this->getServer->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
					if($data == true){
						if(implode($data) == 0){
							$form = $this->getServer->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
								//
								//Join Code
							});
							$form->setTitle("§l§7(§bOwnage Events§7)");
							$form->addLabel("This feature has not been added yet.");
							$form->sendToPlayer($sender);
						}elseif(implode($data) == 1){
							$form = $this->getServer->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, ?array $data = null){
								if(!isset($this->getData["EventCore"]["Event Running"])){
									//Top Kills - Done
									if(implode($data) == "0"){
										if(count($this->getServer->getOnlinePlayers()) >=  0){
											$this->getServer->broadcastMessage("§a↣ an event has started, kill §b" . round(count($this->getServer->getOnlinePlayers()) / 3.4) . " §aDIFFERENT players to win the game!");
											$this->getData["EventCore"]["Event Running"] = true;
											$this->getData["EventCore"]["Current Event"] = "intkills";
											$this->getData["intkills"] = true;
											$this->getData["EventCore"]["intkills"] = round(count($this->getServer->getOnlinePlayers()) / 3.4);
											file_put_contents($this->getPlugin->getDataFolder() . "imbt", json_encode($this->getData));
										}else{
											$sender->sendMessage("§a↣ You must have 8+ players online.");
										}
									}
								}else{
									$sender->sendMessage("§a↣ An event is currently running.");
								}
							});
							$form->setTitle("§l§7(§bOwnage Events§7)");
							$form->addStepSlider("§aChoose Option", ["§7Top Kills", "§7Event Two"]);
							$form->sendToPlayer($sender);
						}elseif(implode($data) == 2){
							if(isset($this->getData["EventCore"]["Event Running"])){
								unlink($this->getPlugin->getDataFolder() . "imbt");
								new Config($this->getPlugin->getDataFolder() . "imbt", Config::JSON);
								$this->getServer->broadcastMessage("§a↣ The event has been terminated.");
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
