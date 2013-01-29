<?php
namespace En\Games;

use En\Games\Crawler\PastGames;
use Doctrine\ORM\EntityManager;

class Indexer
{
    protected $app = null;
    protected $domain = null;

    public function __construct($app, $domain)
    {
        $this->app = $app;
        $this->domain = $domain;
    }

    public function updateGameIndex()
    {
        $em = $this->app['db.orm.em'];

        // We need the domain entity
        $gameDomain = $em->getRepository('En\Entity\GameDomain')->findOneBy(array('name' => $this->domain));

        // $this->updatePastGamesPage($em, $gameDomain, 1);
        $this->indexUnindexedGames($em, $gameDomain);
    }

    /**
     * This function indexes the past games page,
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param \En\Entity\GameDomain $gameDomain
     * @param int $page The past games page to index
     *
     * @Todo Add proper unit testing
     * @Todo Refactor into En\Games\GameList
     */
    protected function updatePastGamesPage(EntityManager $em, \En\Entity\GameDomain $gameDomain, $page = 1)
    {
        // Get the past games page
        echo 'Crawling the past games page ' . $page . '...' . PHP_EOL;
        $crawler = new PastGames($this->domain, $page);
        $gameList = $crawler->getData();

        // Extract the external IDs of the games from the domain
        $gameListByExtId = array();
        $extIds = array();
        foreach($gameList as $game) {
            $extIds[] = $game['ext_id'];
            $gameListByExtId[$game['ext_id']] = $game;
        }

        // Return all the games from the databases, that are already indexed in the retrieved page
        $dql = 'SELECT g FROM En\Entity\Game g INDEX BY g.extId WHERE g.extId in (' . join(',', $extIds) . ')';
        $localGameList = $em->createQuery($dql)->getArrayResult();

        // Get all the games that are currently missing in the database
        $missingGames = array_diff_key(array_flip($extIds), $localGameList);

        print_r($missingGames);

        if (empty($missingGames)) {
            return;
        }

        // Generate the actual entities
        echo 'Adding missing games...' . PHP_EOL;
        foreach($gameListByExtId as $extId => $game) {
            $gameEntity = new \En\Entity\Game();
            $gameEntity->setDomain($gameDomain);
            $gameEntity->fromArray($game);

            $em->persist($gameEntity);
        }
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    protected function indexUnindexedGames(EntityManager $em, \En\Entity\GameDomain $gameDomain)
    {
        // Get the list of unindexed games
        echo 'Getting undindexed games...' . PHP_EOL;
        $unindexedGames = $em->getRepository('En\Entity\Game')->findBy(
            array(
                'isIndexed' => false,
                'domain' => $gameDomain
            )
        );

        // Go over all the unindexed games and index them
        foreach($unindexedGames as $game)
        {
            echo 'Indexing game #' . $game->getNum() . ' - ' . $game->getName() . PHP_EOL;
            if ($this->indexGame($em, $game)) {
                echo "\tGame indexed." . PHP_EOL;

                $game->setIsIndexed(true);
                $em->persist($game);
            } else {
                echo "\tIndexing failed." . PHP_EOL;
            }
        }
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param En\Entity\Game $game
     */
    protected function indexGame(EntityManager $em, \En\Entity\Game $game)
    {
        // First, get the "From Author" text, and index that
        echo 'Indexing game description...' . PHP_EOL;
        // $crawler = new \En\Games\Crawler\GameDetails()
    }
}
