<?php
namespace En\Games;

use En\Games\Crawler\PastGames;

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
        $this->updatePastGamesPage(1);
    }

    /**
     * This function indexes the past games page,
     *
     * @Todo Add proper unit testing
     * @Todo Refactor into En\Games\GameList
     */
    protected function updatePastGamesPage($page = 1)
    {
        /**
         * @var Doctrine\ORM\EntityManager $em
         */
        $em = $this->app['db.orm.em'];

        echo 'Crawling the past games page ' . $page . '...';
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

        if (empty($missingGames)) {
            return;
        }

        // We need the domain entity
        $gameDomain = $em->getRepository('En\Entity\GameDomain')->findOneBy(array('name' => $this->domain));

        // Generate the actual entities
        echo 'Adding missing games...';
        foreach($gameListByExtId as $extId => $game) {
            $gameEntity = new \En\Entity\Game();
            $gameEntity->setDomain($gameDomain);
            $gameEntity->fromArray($game);

            $em->persist($gameEntity);
        }

        $em->flush();
    }
}
