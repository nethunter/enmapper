<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\DriverManager;

$console = new Application('En-Mapper', '0.1');

$console
        ->register('assetic:dump')
        ->setDescription('Dumps all assets to the filesystem')
        ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
                    $dumper = $app['assetic.dumper'];
                    if (isset($app['twig'])) {
                        $dumper->addTwigAssets();
                    }
                    $dumper->dumpAssets();
                    $output->writeln('<info>Dump finished</info>');
                });
                
$console
        ->register('en:update:games')
        ->setDescription('Index the list of past games')
        ->addOption(
                'dev', null, InputOption::VALUE_OPTIONAL, 
                'Use local stub, instead of production code', false)
        ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
            $dev = $input->getOption('dev');
            
            $pastGames = new En\Games\Crawler\PastGames($app['en_domain'], 1, $dev);
            $pastGameList = $pastGames->getList();
            
            $output->writeln(print_r($pastGameList, true));
        });

return $console;
