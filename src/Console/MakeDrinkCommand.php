<?php

namespace Pdpaola\CoffeeMachine\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


use Pdpaola\CoffeeMachine\Entity\Drink;
use Pdpaola\CoffeeMachine\Factory\DrinkFactory;

class MakeDrinkCommand extends Command
{
    protected static $defaultName = 'app:order-drink';

    protected function configure()
    {
        $this->addArgument(
            'drink-type',
            InputArgument::REQUIRED,
            'The type of the drink. (Tea, Coffee or Chocolate)'
        );

        $this->addArgument(
            'money',
            InputArgument::REQUIRED,
            'The amount of money given by the user'
        );

        $this->addArgument(
            'sugars',
            InputArgument::OPTIONAL,
            'The number of sugars you want. (0, 1, 2)',
            0
        );

        $this->addOption(
            'extra-hot',
            'e',
            InputOption::VALUE_NONE,
            $description = 'If the user wants to make the drink extra hot'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $drinkType = strtolower($input->getArgument('drink-type'));
            $money = $input->getArgument('money');
            $sugars = $input->getArgument('sugars');
            $stick = $sugars > 0;
            $extraHot = $input->getOption('extra-hot');

            $order = DrinkFactory::createDrink($drinkType, $money, $sugars, $extraHot);
            $this->save($order, $stick);

            $output->write('You have ordered a ' . $drinkType);
            if ($extraHot) {
                $output->write(' extra hot');
            }
            if ($stick) {
                $output->write(' with ' . $sugars . ' sugars (stick included)');
            }
            $output->writeln('');
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    private function save(Drink $drink, bool $stick)
    {
        $pdo = MysqlPdoClient::getPdo();

        $stmt = $pdo->prepare('INSERT INTO orders (drink_type, sugars, stick, extra_hot) VALUES (:drink_type, :sugars, :stick, :extra_hot)');
        $stmt->execute([
            'drink_type' => $drink->getType(),
            'sugars' => $drink->getSugar(),
            'stick' => $stick ?: 0,
            'extra_hot' => $drink->getExtraHot() ?: 0,
        ]);
    }
}
