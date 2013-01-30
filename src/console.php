<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Input\InputArgument;
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
    ->register('en:games:add-domain')
    ->setDescription('Add another domain to the list')
    ->addArgument(
        'domain', InputArgument::REQUIRED, 'The domain to be added'
    )
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
    $domain = $input->getArgument('domain');

    $em = $app['db.orm.em'];
    $gameDomain = $em->getRepository('En\Entity\GameDomain')->findOneBy(array('name' => $domain));

    if (null == $gameDomain) {
        $output->writeln('Domain doesn\'t exist. Addding.');

        $gameDomain = new \En\Entity\GameDomain();
        $gameDomain->setName($domain);
        $em->persist($gameDomain);
        $em->flush();

        $output->writeln('Domain ' . $domain . ' added.');
    } else {
        $output->writeln('Domain already exists. Not adding.');
    }
});
$console
    ->register('en:index:past-games')
    ->setDescription('Index the list of past games')
    ->addArgument('page', InputArgument::OPTIONAL, 'Past games page to index', 1)
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {

    $gameList = new En\Games\Indexer($app);
    $gameResult = $gameList->updatePastGameIndex($input->getArgument('page'));

    $output->writeln(print_r($gameResult, true));
});
$console
    ->register('en:index:missing-games')
    ->setDescription('Index descriptions and scenarios of unindexed games')
    ->addArgument(
        'gameId', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Optional gameIDs of games to index'
    )
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
    $gameList = new En\Games\Indexer($app);
    $gameResult = $gameList->indexMissingGames($input->getArgument('gameId'));

    $output->writeln(print_r($gameResult, true));
});

return $console;
