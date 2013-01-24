<?php
namespace En\Games;

use En\Games\Crawler\PastGames;

class GameList
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
        $crawler = new PastGames($this->domain, 1);
        $gameList = $crawler->getData();

        $extIds = array();
        foreach($gameList as $game) {
            $extIds[] = $game['ext_id'];
        }

        $localGameList = $em->getRepository('En\Games\Game')->findBy(array('ext_id' => $extIds));
        print_r($localGameList);
    }
}
