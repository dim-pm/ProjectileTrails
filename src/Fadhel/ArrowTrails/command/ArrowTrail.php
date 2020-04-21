<?php

/**
 * Copyright 2020 Fadhel
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace Fadhel\ArrowTrails\command;

use Fadhel\ArrowTrails\Main;
use Fadhel\ArrowTrails\utils\form\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ArrowTrail extends Command implements PluginIdentifiableCommand
{
    /**
     * @var Main
     */
    protected $plugin;

    /**
     * ArrowTrail constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("arrowtrails", "Select your arrows particle", "", ["at"]);
    }

    /**
     * @param Player $player
     */
    private function sendForm(Player $player): void
    {
        $form = new SimpleForm(function (Player $event, $data) {
            $player = $event->getPlayer();
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    if ($player->hasPermission("arrowtrails.angryvillager")) {
                        $this->plugin->data[$player->getName()] = 1;
                        $player->sendMessage(TextFormat::GREEN . "You've changed your arrow particle to " . TextFormat::AQUA . "Angry Villager" . TextFormat::GREEN . ".");
                    } else {
                        $player->sendMessage(TextFormat::RED . "You don't have permission to use this trail.");
                    }
                    break;
                case 1:
                    if ($player->hasPermission("arrowtrails.heart")) {
                        $this->plugin->data[$player->getName()] = 2;
                        $player->sendMessage(TextFormat::GREEN . "You've changed your arrow particle to " . TextFormat::AQUA . "Heart" . TextFormat::GREEN . ".");
                    } else {
                        $player->sendMessage(TextFormat::RED . "You don't have permission to use this trail.");
                    }
                    break;
                case 2:
                    if ($player->hasPermission("arrowtrails.happyvillager")) {
                        $this->plugin->data[$player->getName()] = 3;
                        $player->sendMessage(TextFormat::GREEN . "You've changed your arrow particle to " . TextFormat::AQUA . "Happy Villager" . TextFormat::GREEN . ".");
                    } else {
                        $player->sendMessage(TextFormat::RED . "You don't have permission to use this trail.");
                    }
                    break;
                case 3:
                    if ($player->hasPermission("arrowtrails.flame")) {
                        $this->plugin->data[$player->getName()] = 4;
                        $player->sendMessage(TextFormat::GREEN . "You've changed your arrow particle to " . TextFormat::AQUA . "Flame" . TextFormat::GREEN . ".");
                    } else {
                        $player->sendMessage(TextFormat::RED . "You don't have permission to use this trail.");
                    }
                    break;
                case 4:
                    if ($player->hasPermission("arrowtrails.lava")) {
                        $this->plugin->data[$player->getName()] = 5;
                        $player->sendMessage(TextFormat::GREEN . "You've changed your arrow particle to " . TextFormat::AQUA . "Lava" . TextFormat::GREEN . ".");
                    } else {
                        $player->sendMessage(TextFormat::RED . "You don't have permission to use this trail.");
                    }
                    break;
                case 5:
                    if ($player->hasPermission("arrowtrails.lavadrip")) {
                        $this->plugin->data[$player->getName()] = 6;
                        $player->sendMessage(TextFormat::GREEN . "You've changed your arrow particle to " . TextFormat::AQUA . "Lava drip" . TextFormat::GREEN . ".");
                    } else {
                        $player->sendMessage(TextFormat::RED . "You don't have permission to use this trail.");
                    }
                    break;
                case 6:
                    if ($player->hasPermission("arrowtrails.waterdrip")) {
                        $this->plugin->data[$player->getName()] = 7;
                        $player->sendMessage(TextFormat::GREEN . "You've changed your arrow particle to " . TextFormat::AQUA . "Water drip" . TextFormat::GREEN . ".");
                    } else {
                        $player->sendMessage(TextFormat::RED . "You don't have permission to use this trail.");
                    }
                    break;
                case 7:
                    if ($player->hasPermission("arrowtrails.enchantment")) {
                        $this->plugin->data[$player->getName()] = 8;
                        $player->sendMessage(TextFormat::GREEN . "You've changed your arrow particle to " . TextFormat::AQUA . "Enchantment" . TextFormat::GREEN . ".");
                    } else {
                        $player->sendMessage(TextFormat::RED . "You don't have permission to use this trail.");
                    }
                    break;
                case 8:
                    $this->plugin->data[$player->getName()] = null;
                    $player->sendMessage(TextFormat::GREEN . "You've disabled your arrow particle.");
                    break;
                case 9:
                    $player->sendMessage(TextFormat::AQUA . "ArrowTrails> " . TextFormat::GREEN . "This plugin was made by "  . TextFormat::AQUA . "Fadhel " . TextFormat::GREEN . "aka FADHEL GAMER or FadhelFS. This plugin is an open-source project, You can fork it and modify it, However you cannot change the author of the plugin or sell it.\nSubscribe to get more awesome plugins for PocketMine-MP at ". TextFormat::AQUA . "https://youtube.com/c/FadhelFS");
            }
        });
        $form->setTitle("ArrowTrails");
        $form->setContent("Select your favorite particle:");
        $form->addButton(TextFormat::DARK_PURPLE . "Angry Villager");
        $form->addButton(TextFormat::RED . "Heart");
        $form->addButton(TextFormat::GREEN . "Happy Villager");
        $form->addButton(TextFormat::YELLOW . "Flame");
        $form->addButton(TextFormat::GOLD . "Lava");
        $form->addButton(TextFormat::GOLD . "Lava drip");
        $form->addButton(TextFormat::AQUA . "Water drip");
        $form->addButton(TextFormat::BLACK . "Enchantment");
        $form->addButton(TextFormat::DARK_RED . "Disable");
        $form->addButton(TextFormat::LIGHT_PURPLE . TextFormat::BOLD . "Plugin Credits");
        $form->addButton(TextFormat::RED . TextFormat::BOLD . "Exit");
        $player->sendForm($form);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Run this command in-game.");
            return;
        }
        $this->sendForm($sender);
    }

    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}
