<?php
namespace En\Games\Crawler;

use En\CrawlerClient;

class PastGames extends CrawlerAbstract
{
    protected $url = '/Games.aspx';
    protected $page = null;
    protected $games = null;

    public function __construct($domain, $page = 1)
    {
        $this->domain = $domain;
        $this->page = $page;
    }
        
    public function getData()
    {
        $crawler = $this->getCrawler();
        
        $gamesTable = $crawler->filterXPath(
            '//td[@id=\'tdContentCenter\']/table/'
            . 'tr/td/table/tr/td/table/tr[1]/td/table/tr'
        );
        $gamesCount = $gamesTable->count();
        
        $gamesTableDetails = array();
        for ($i = 0; $i < $gamesCount; $i++) {
            $game = $gamesTable->eq($i);
            $number = $game->filter('td span span')->text();
         
            $gameDetails = $game->filterXPath('//td[4]/a');
            
            $gameData = array(
                'type' => $game->filterXPath('//tr/td[2]/span')->text(),
                'number' => $game->filter('td span span')->text(),
                'title' => $gameDetails->text(),
                'link' => $gameDetails->attr('href')
            );
            
            if ('Quest' == $gameData['type']) {
                $gamesTableDetails[] = $gameData;
            }
        }
        
        return $gamesTableDetails;
    }
}
