<?php
namespace En\Games;

use En\Entity\GameDomain;
use En\Entity\Game;
use En\Entity\GameLevel;
use En\Entity\Location;

use En\Games\Crawler\PastGames;
use Doctrine\ORM\EntityManager;

class Indexer
{
    protected $app = null;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function updatePastGameIndex($page = 1)
    {
        $em = $this->app['db.orm.em'];

        $missingGames = array();

        // We need the domain entity
        echo 'Retrieving the list of domains...' . PHP_EOL;
        $gameDomains = $em->getRepository('En\Entity\GameDomain')->findAll();

        foreach($gameDomains as $gameDomain) {
            echo 'Indexing ' . $gameDomain->getName() . PHP_EOL;
            $missingGames += $this->indexPastGamesPage($em, $gameDomain, $page);
        }

        return $missingGames;
    }

    /**
     * Retrieve all the unindexed games, and index their description and scenario
     *
     * @param array $gameIds An array of external gameIDs to index
     */
    public function indexMissingGames($gameIds = null)
    {
        $em = $this->app['db.orm.em'];

        // Get the list of unindexed games
        echo 'Getting undindexed games...' . PHP_EOL;
        if (null == $gameIds) {
            $unindexedGames = $em->getRepository('En\Entity\Game')->findByIsIndexed(false);
        } else {
            $unindexedGames = $em->getRepository('En\Entity\Game')->findByExtId($gameIds);
        }

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

        // Persist the data
        $em->flush();
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
    protected function indexPastGamesPage(EntityManager $em, GameDomain $gameDomain, $page = 1)
    {
        // Get the past games page
        echo 'Crawling the past games page ' . $page . '...' . PHP_EOL;
        $crawler = new PastGames($gameDomain->getName(), $page);
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
            return $missingGames;
        }

        // Generate the actual entities
        echo 'Adding missing games...' . PHP_EOL;
        foreach($gameListByExtId as $extId => $game) {
            $gameEntity = new \En\Entity\Game();
            $gameEntity->setDomain($gameDomain);
            $gameEntity->fromArray($game);

            $em->persist($gameEntity);
        }

        $em->flush();
        return $missingGames;
    }

    /**
     * Index a specific game, by GameEntity
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param \En\Entity\Game $game
     * @return Boolean Success?
     */
    protected function indexGame(EntityManager $em, Game $game)
    {
        // Get the domain that the game was run on
        $gameDomain = $game->getDomain();

        // First, get the "From Author" text, and index that
        echo 'Indexing game description...' . PHP_EOL;
        $crawler = new Crawler\GameDetails($gameDomain->getName(), $game->getExtId());
        $gameDetails = $crawler->getData();

        // Generate a fake level for the briefing
        $brief = new GameLevel();
        $brief
            ->setNum(0)
            ->setName('From The Authors')
            ->setLink($crawler->getUrl())
            ->setContent($gameDetails);
        $game->addLevel($brief);
        $this->generateLocationEntities($brief);
        $em->persist($brief);

        // Index the rest of the games levels
        echo 'Indexing game scenario...' . PHP_EOL;
        $crawler = new Crawler\GameScenario($gameDomain->getName(), $game->getExtId());
        $gameScenario = $crawler->getData();

        // Index all the game levels
        foreach($gameScenario as $levelContent) {
            echo "\tParsing level " . $levelContent['num'] . ' - ' . $levelContent['name'] . '...' . PHP_EOL;

            $level = new GameLevel();
            $level
                ->setNum($levelContent['num'])
                ->setName($levelContent['name'])
                ->setLink($crawler->getUrl() . '#' . $levelContent['num'])
                ->setContent($levelContent['content']);
            $this->generateLocationEntities($level);
            $game->addLevel($level);
            $em->persist($level);
        }

        return true;
    }

    protected function generateLocationEntities(GameLevel $level)
    {
        echo "\tDetecting locations for level " . $level->getNum() . '...' . PHP_EOL;
        $content = $level->getContent();
        $locationFilter = new \En\Games\LocationFilter($content);
        $coordinates = $locationFilter->getCoordinates();
        echo "\tFound " . count($coordinates) . ' locations...' . PHP_EOL;

        foreach($coordinates as $coordinate) {
            $location = new Location();
            $location
                ->setLat($coordinate[0])
                ->setLng($coordinate[1]);
            $level->addLocation($location);
        }
    }
}
