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

namespace Fadhel\ArrowTrails\entity;

use pocketmine\entity\projectile\Arrow as PMArrow;
use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\EnchantmentTableParticle;
use pocketmine\level\particle\EnchantParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\LavaDripParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\WaterDripParticle;
use pocketmine\Player;

class Arrow extends PMArrow
{
    /**
     * @var Player
     */
    public $owner;

    /**
     * @var int
     */
    public $trail = 0;

    public function sendTrail(): void
    {
        switch ($this->trail) {
            case 1:
                $this->getLevel()->addParticle(new AngryVillagerParticle($this->asVector3()));
                break;
            case 2:
                $this->getLevel()->addParticle(new HeartParticle($this->asVector3()));
                break;
            case 3:
                $this->getLevel()->addParticle(new HappyVillagerParticle($this->asVector3()));
                break;
            case 4:
                $this->getLevel()->addParticle(new FlameParticle($this->asVector3()));
                break;
            case 5:
                $this->getLevel()->addParticle(new LavaParticle($this->asVector3()));
                break;
            case 6:
                $this->getLevel()->addParticle(new LavaDripParticle($this->asVector3()));
                break;
            case 7:
                $this->getLevel()->addParticle(new WaterDripParticle($this->asVector3()));
                break;
            case 8:
                $this->getLevel()->addParticle(new EnchantmentTableParticle($this->asVector3()));
        }
    }

    public function entityBaseTick(int $tickDiff = 1): bool
    {
        if ($this->owner instanceof Player and $this->owner->isOnline() and !$this->isCollided) {
            $this->sendTrail();
        } else {
            $this->owner = null;
        }
        return parent::entityBaseTick($tickDiff);
    }
}
