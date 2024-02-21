<?php

namespace Pdpaola\CoffeeMachine\Console;

use Symfony\Component\Console\Command\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Pdpaola\CoffeeMachine\Factory\DrinkFactory;


class SearchSalesCommand extends Command
{
    protected static $defaultName = 'app:sales-drink';
    

    protected function configure()
    {}


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $pdo = MysqlPdoClient::getPdo();
            $stmt = $pdo->prepare('SELECT drink_type, COUNT(*) as count FROM orders GROUP BY drink_type');
            $stmt->execute();
            $results = $stmt->fetchAll();
            $output->writeln('|Drink    |Money|'           );            
            $output->writeln('|---------|-----|'           );   
            foreach ($results as $row) {
                $total= $row['count'] * DrinkFactory::getPrice($row['drink_type']);
                 $output->writeln('|'.$row['drink_type'].'  =>  '. $total.'|'           );            
            }
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
        }
    }


}