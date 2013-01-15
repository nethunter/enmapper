<?php
namespace En\Games\Crawler;

use En\CrawlerClient;

class GameDetails extends CrawlerAbstract {
    protected $_url = '/GameDetails.aspx';
    protected $_game_id = null;
    protected $_pages = null;
    protected $_games = null;
    
    public function __construct($domain, $gameId)
    {
        parent::__construct($domain);
        
        $this->_url .= '?gid=' . $gameId;
    }
    
    public function getData()
    {
        $crawler = $this->getCrawler();
        
        $gamesTable = $crawler->filterXPath('//td[@id=\'tdContentCenter\']/table/'
                . 'tr/td/table/tr/td/table/tr[1]/td/table/tr');
        $gamesCount = $gamesTable->count();
        
        for($i = 0; $i < $gamesCount; $i++) {
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