<?php

declare(strict_types=1);

namespace Fadhel\ProjectileTrails\form;

use pocketmine\form\Form as IForm;
use pocketmine\Player;

class Form implements IForm
{
    protected $data;
    protected $callable;
    protected $labelMap = [];

    public function __construct(?callable $callable)
    {
        $this->data["type"] = "form";
        $this->data["title"] = "";
        $this->data["content"] = "";
        $this->data["buttons"] = [];
        $this->callable = $callable;
    }

    public function processData(&$data): void
    {
        $data = $this->labelMap[$data] ?? null;
    }

    public function handleResponse(Player $player, $data): void
    {
        $this->processData($data);
        $callable = $this->getCallable();
        if ($callable !== null) {
            $callable($player, $data);
        }
    }

    public function jsonSerialize()
    {
        return $this->data;
    }

    public function getCallable(): ?callable
    {
        return $this->callable;
    }

    public function setTitle(string $title): void
    {
        $this->data["title"] = $title;
    }

    public function setContent(string $content): void
    {
        $this->data["content"] = $content;
    }

    public function addButton(string $text, int $imageType = -1, string $imagePath = "", ?string $label = null): void
    {
        $content = ["text" => $text];
        if ($imageType !== -1) {
            $content["image"]["type"] = $imageType === 0 ? "path" : "url";
            $content["image"]["data"] = $imagePath;
        }
        $this->data["buttons"][] = $content;
        $this->labelMap[] = $label ?? count($this->labelMap);
    }
}
