<?php

namespace tutorial;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
class Main extends PluginBase implements Listener {
	public function onEnable() {
		
	}
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		$cfg = new Config($this->getDataFolder."config.yml", Config::YAML);
		$cfg = $cfg->getAll();
		$cfg = $cfg[$command];
		if (! is_null($cfg["cost"])) {
			if (!$cfg["cost"] >= EconomyAPI::getInstance()->myMoney($sender)) {
				$sender->sendMessage(TextFormat::RED, "명령어를 실행하기위한 돈이 부족합니다");
				return false;
			}
		} else {
			if (! is_null($cfg["cooltime"])) {
				if (! is_null( shmop_read( shmop_open( base_convert($sender, 16, 2), 'c', 0755, 1024), 0, 11))) {
					if ( shmop_read( shmop_open( base_convert($sender, 16, 2), 'c', 0755, 1024), 0, 11) <= time()){
						shmop_write( shmop_open( base_convert($sender, 16, 2), 'c', 0755, 1024), date() + $cfg["cooltime"], 0);
						return true;
					} else {
						$cooltime = shmop_read( shmop_open( base_convert($sender, 16, 2), 'c', 0755, 1024), 0, 11) - date();
						$sender->sendMessage(TextFormat::RED, "{$cooltime}초 후에 사용해 주십시오");
						return false;
					}
				} else {
					shmop_write( shmop_open( base_convert($sender, 16, 2), 'c', 0755, 1024), date() + $cfg["cooltime"], 0);
					return true;
				}
			}
		}
	}
}
?>