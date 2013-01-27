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

    /**
     * @Todo Add proper unit testing
     */
    public function updateGameIndex()
    {
        $em = $this->app['db.orm.em'];

        // $crawler = new PastGames($this->domain, 1);
        // $gameList = $crawler->getData();
        $gameList = array(
            array(
                'type' => 'Quest',
                'number' => 85,
                'title' => 'Эта музыка будет вечной',
                'ext_id' => 39352,
                'link' => '/GameDetails.aspx?gid=39352'
            )
        );

        $extIds = array();
        foreach($gameList as $game) {
            $extIds[] = $game['ext_id'];
        }

        $localGameList = $em->getRepository('En\Entity\Game')->findBy(array('extId' => $extIds));
    }
}
