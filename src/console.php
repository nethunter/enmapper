<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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

            $gameList = new En\Games\Indexer($app, $app['en_domain']);
            $gameResult = $gameList->updateGameIndex();

            $output->writeln(print_r($gameResult, true));
        });

                
$console
        ->register('en:update:load_sample_data')
        ->setDescription('Fill the DB with sample data, for debug')
        ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
            $em = $app['db.orm.em'];
            
            $domain = new En\Entity\GameDomain();
            $domain->setName('rusisrael.en.cx');
            $em->persist($domain);
            
            $game = new En\Entity\Game();
            $game->setDomain($domain);
            $game->setExtId(39352);
            $game->setName('Эта музыка будет вечной');
            $game->setNum(85);
            $game->setIsIndexed(false);
            $game->setLink('/GameDetails.aspx?gid=39352');
            $game->setType('quest');
            $em->persist($game);
            
            $level = new En\Entity\GameLevel();
            $level->setGame($game);
            $level->setName('Level #24 "Последняя заглушка"');
            $level->setNum(24);
            $content = "Autopass: in 5 minutes\t\t\t\t\t\tTask for "
                    . "allНу что ж, молодцы!\rБанально надеемся, что вам "
                    . "тупо понравилось! :)\rЖдем вас в баре Пророк-Сайгон, "
                    . "ул. Левонтин 11, Тель Авив.";
            $level->setContent($content);
            $em->persist($level);
            
            $coordinates = array(
                array(
                    'position' => array(31.921452, 34.882401),
                    'level' => 'От автора игры'
                ),
                array(
                    'position' => array(31.860161, 34.923788),
                    'level' => 'Level #5 "Тель Гезер"'
                ),
                array(
                    'position' => array(31.964167, 34.783056 ),
                    'level' => 'Level #9 "Ришон - пианино"'
                ),
                array(
                    'position' => array(31.964167, 34.783056 ),
                    'level' => 'Level #9 "Ришон - пианино"'
                ),
                array(
                    'position' => array(32.101478, 34.827758),
                    'level' => 'Level #12 "Агентский уровень"'
                ),
                array(
                    'position' => array(32.083373, 34.9575488),
                    'level' => 'Level #18 "Могила Шейха"'
                )
            );
            
            foreach($coordinates as $coordinate) {
                $location = new En\Entity\Location();
                $location->setLevel($level);
                $location->setLat($coordinate['position'][0]);
                $location->setLng($coordinate['position'][1]);
                $em->persist($location);
            }
            
            $em->flush();
        });

return $console;
