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

namespace Fadhel\ProjectileTrails;

use Fadhel\ProjectileTrails\entity\Arrow;
use Fadhel\ProjectileTrails\entity\Egg;
use Fadhel\ProjectileTrails\entity\Snowball;

use pocketmine\event\Listener; 
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;

use pocketmine\Player;

class Listeners implements Listener
{
    /**
     * @var Main
     */
    protected $plugin;

    /**
     * Listeners constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerLoginEvent $event
     */
    public function onLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        $this->plugin->sync($player->getName());
    }

    /**
     * @param ProjectileLaunchEvent $event
     */
    public function onLaunch(ProjectileLaunchEvent $event): void
    {
        $entity = $event->getEntity();
        $player = $entity->getOwningEntity();
        if ($player instanceof Player) {
            if ($this->plugin->getConfig()->get("enable-arrow") and $entity instanceof Arrow or $this->plugin->getConfig()->get("enable-egg") and $entity instanceof Egg or $this->plugin->getConfig()->get("enable-snowball") and $entity instanceof Snowball) {
                $entity->plugin = $this->plugin;
                $entity->owner = $player;
            }
        }
    }
}
