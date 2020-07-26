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

namespace Fadhel\ProjectileTrails\command;

use Fadhel\ProjectileTrails\Main;
use Fadhel\ProjectileTrails\form\Form;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class Trails extends Command implements PluginIdentifiableCommand
{
    /**
     * @var Main
     */
    protected $plugin;

    /**
     * Trails constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("projectiletrails", "Select your projectile trails", "", ["pt"]);
    }

    /**
     * @param Player $player
     */
    private function sendForm(Player $player): void
    {
        $form = new Form(function (Player $event, $data) {
            $player = $event->getPlayer();
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $this->plugin->updateParticle($player, 1);
                    break;
                case 1:
                    $this->plugin->updateParticle($player, 2);
                    break;
                case 2:
                    $this->plugin->updateParticle($player, 3);
                    break;
                case 3:
                    $this->plugin->updateParticle($player, 4);
                    break;
                case 4:
                    $this->plugin->updateParticle($player, 5);
                    break;
                case 5:
                    $this->plugin->updateParticle($player, 6);
                    break;
                case 6:
                    $this->plugin->updateParticle($player, 7);
                    break;
                case 7:
                    $this->plugin->updateParticle($player, 8);
                    break;
                case 8:
                    $this->plugin->updateParticle($player, 9);
                    break;
                case 9:
                    $this->plugin->updateParticle($player, 10);
                    break;
                case 10:
                    $this->plugin->updateParticle($player, 11);
                    break;
                case 11:
                    $this->plugin->updateParticle($player, 12);
                    break;
                case 12:
                    $this->plugin->updateParticle($player, 13);
                    break;
                case 13:
                    $this->plugin->updateParticle($player, 0);
            }
        });
        $form->setTitle("Projectile Trails");
        $form->setContent("Select your favorite particle:");
        $form->addButton($this->plugin->check($player, 1));
        $form->addButton($this->plugin->check($player, 2));
        $form->addButton($this->plugin->check($player, 3));
        $form->addButton($this->plugin->check($player, 4));
        $form->addButton($this->plugin->check($player, 5));
        $form->addButton($this->plugin->check($player, 6));
        $form->addButton($this->plugin->check($player, 7));
        $form->addButton($this->plugin->check($player, 8));
        $form->addButton($this->plugin->check($player, 9));
        $form->addButton($this->plugin->check($player, 10));
        $form->addButton($this->plugin->check($player, 11));
        $form->addButton($this->plugin->check($player, 12));
        $form->addButton($this->plugin->check($player, 13));
        $form->addButton(TextFormat::GOLD . "Turn Off");
        $form->addButton(TextFormat::RED . TextFormat::BOLD . "Exit", 0, "textures/blocks/barrier");
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

    /**
     * @return Main
     */
    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
}
