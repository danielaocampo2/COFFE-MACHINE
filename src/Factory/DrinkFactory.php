<?php

namespace Pdpaola\CoffeeMachine\Factory;

use Pdpaola\CoffeeMachine\Entity\Drink;
use Exception;

class DrinkFactory
{

    private static $price = [
        'tea' => 0.4,
        'coffee' => 0.5,
        'chocolate' => 0.6
    ];

    public static function createDrink(string $drinkType, float $money, int $sugars = 0, bool $extraHot = false): Drink
    {
        self::validateDrinkType($drinkType);

        $price = self::$price[$drinkType];
        if ($money < $price) {
            throw new Exception('The ' . $drinkType . ' costs ' . $price . '.');
        }
        if ($sugars < 0 || $sugars > 2) {
            throw new Exception('The number of sugars should be between 0 and 2.');
        }
        return new Drink($drinkType, $sugars, $extraHot);
    }

    private static function validateDrinkType(string $drinkType)
    {
        if (!isset(self::$price[$drinkType])) {
            throw new Exception('The drink type should be tea, coffee or chocolate.');
        }
    }

    public static function getPrice(string $drinkType): float
    {
        self::validateDrinkType($drinkType);
        return self::$price[$drinkType];
    }
}
