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

use Fadhel\ProjectileTrails\command\Trails;
use Fadhel\ProjectileTrails\entity\Arrow;
use Fadhel\ProjectileTrails\entity\Egg;
use Fadhel\ProjectileTrails\entity\Snowball;

use pocketmine\entity\Entity;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\WaterParticle;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\EnchantmentTableParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\HappyVillagerParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\LavaDripParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\WaterDripParticle;

use SQLite3;

class Main extends PluginBase
{
    protected $database;
    private $particles =
        [
            1 => "Angry Villager", 2 => "Enchantment", 3 => "Explode",
            4 => "Happy Villager", 5 => "Heart", 6 => "Flame",
            7 => "Lava", 8 => "Lava Drip", 9 => "Portal",
            10 => "Rainbow Dust", 11 => "Smoke", 12 => "Water",
            13 => "Water Drip"
        ];

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("projectiletrails", new Trails($this));
        $this->getServer()->getPluginManager()->registerEvents(new Listeners($this), $this);
        $this->database = new SQLite3($this->getDataFolder() . "players.db");
        $this->database->exec("CREATE TABLE IF NOT EXISTS players(player VARCHAR(16), particle INT DEFAULT 0);");
        $this->init();
    }

    private function init(): void
    {
        if ($this->getConfig()->get("enable-arrow")) {
            Entity::registerEntity(Arrow::class, true);
        }
        if ($this->getConfig()->get("enable-egg")) {
            Entity::registerEntity(Egg::class, true);
        }
        if ($this->getConfig()->get("enable-snowball")) {
            Entity::registerEntity(Snowball::class, true);
        }
    }

    public function spawnParticle(Player $player, Entity $entity): void
    {
        switch ($this->getParticle($player->getName())) {
            case 1:
                $entity->getLevel()->addParticle(new AngryVillagerParticle($entity->asVector3()));
                break;
            case 2:
                $entity->getLevel()->addParticle(new EnchantmentTableParticle($entity->asVector3()));
                break;
            case 3:
                $entity->getLevel()->addParticle(new ExplodeParticle($entity->asVector3()));
                break;
            case 4:
                $entity->getLevel()->addParticle(new HappyVillagerParticle($entity->asVector3()));
                break;
            case 5:
                $entity->getLevel()->addParticle(new HeartParticle($entity->asVector3()));
                break;
            case 6:
                $entity->getLevel()->addParticle(new FlameParticle($entity->asVector3()));
                break;
            case 7:
                $entity->getLevel()->addParticle(new LavaParticle($entity->asVector3()));
                break;
            case 8:
                $entity->getLevel()->addParticle(new LavaDripParticle($entity->asVector3()));
                break;
            case 9:
                $entity->getLevel()->addParticle(new PortalParticle($entity->asVector3()));
                break;
            case 10:
                $entity->getLevel()->addParticle(new DustParticle($entity->asVector3(), mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)));
                break;
            case 11:
                $entity->getLevel()->addParticle(new SmokeParticle($entity->asVector3()));
                break;
            case 12:
                $entity->getLevel()->addParticle(new WaterParticle($entity->asVector3()));
                break;
            case 13:
                $entity->getLevel()->addParticle(new WaterDripParticle($entity->asVector3()));
        }
    }

    public function check(Player $player, int $particle): string
    {
        if ($player->hasPermission("projectiletrails." . $this->particles[$particle])) {
            $message = TextFormat::GREEN . $this->particles[$particle];
        } else {
            $message = TextFormat::RED . $this->particles[$particle];
        }
        if ($this->getParticle($player->getName()) === $particle) {
            $message = TextFormat::YELLOW . $this->particles[$particle];
        }
        return $message;
    }

    /**
     * @param string $player
     */
    public function sync(string $player): void
    {
        $stmt = $this->database->prepare("SELECT particle FROM players WHERE player = :player");
        $stmt->bindValue(":player", $player);
        $result = $stmt->execute();
        if ($result->fetchArray(SQLITE3_ASSOC)["particle"] === null) {
            $stmt = $this->database->prepare("INSERT INTO players(player, particle) VALUES(:player, :particle);");
            $stmt->bindValue(":player", strtolower($player));
            $stmt->execute();
        }
    }

    /**
     * @param string $player
     * @return mixed
     */
    public function getParticle(string $player)
    {
        $stmt = $this->database->prepare("SELECT particle FROM players WHERE player = :player");
        $stmt->bindValue(":player", strtolower($player));
        $result = $stmt->execute();
        return $result->fetchArray(SQLITE3_ASSOC)["particle"];
    }

    /**
     * @param Player $player
     * @param int $particle
     */
    public function updateParticle(Player $player, int $particle): void
    {
        if ($this->getParticle($player->getName()) === $particle) {
            $player->sendMessage(TextFormat::colorize(str_replace("{particle}", $this->particles[$particle], $this->getConfig()->get("error-same"))));
            return;
        }
        if ($player->hasPermission("projectiletrails." . strtolower(str_replace(" ", "", $particle)))) {
            $stmt = $this->database->prepare("UPDATE players SET particle = :particle WHERE player = :player");
            $stmt->bindValue(":particle", $particle);
            $stmt->bindValue(":player", strtolower($player->getName()));
            $stmt->execute();
            $player->sendMessage($particle > 0 ? TextFormat::colorize(str_replace("{particle}", $this->particles[$particle], $this->getConfig()->get("change-message"))) : $this->getConfig()->get("disable-message"));
        } else {
            $player->sendMessage(TextFormat::colorize(str_replace("{particle}", $this->particles[$particle], $this->getConfig()->get("error-perms"))));
        }
    }
}
