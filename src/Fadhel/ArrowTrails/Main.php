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

namespace Fadhel\ArrowTrails;

use Fadhel\ArrowTrails\command\ArrowTrail;
use Fadhel\ArrowTrails\entity\Arrow;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    /**
     * @var string[]
     */
    public $data = [];

    public function onEnable()
    {
        $this->getServer()->getCommandMap()->register("arrowtrails", new ArrowTrail($this));
        $this->getServer()->getPluginManager()->registerEvents(new Listeners($this), $this);
        Entity::registerEntity(Arrow::class, true);
    }

    public function onDisable()
    {
        $this->data = [];
    }
}
