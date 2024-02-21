<?php

namespace Pdpaola\CoffeeMachine\Entity;

class Drink
{
    private $type;
    private $sugar;
    private $extraHot;

    public function __construct(string $type, int $sugar, bool $extraHot)
    {
        $this->type = $type;
        $this->sugar = $sugar;
        $this->extraHot = $extraHot;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSugar(): int
    {
        return $this->sugar;
    }

    public function getExtraHot(): bool
    {
        return $this->extraHot;
    }
}
