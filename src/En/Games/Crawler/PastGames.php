<?php
namespace En\Games\Crawler;

use En\CrawlerClient;

class PastGames {
    protected $_domain = null;
    protected $_url = '/Games.aspx';
    protected $_pages = null;
    protected $_games = null;

    public function __construct($domain, $pages = 1) {
        $this->_domain = $domain;
        $this->_pages = $pages;
    }
    
    public function getCrawler()
    {
        $request_url = 'http://' . $this->_domain . $this->_url;

        $client = new CrawlerClient();
        $crawler = $client->request('GET', $request_url);
        
        return $crawler;
    }
    
    public function getList()
    {
        $crawler = $this->getCrawler();
        
        $gamesTable = $crawler->filterXPath('//td[@id=\'tdContentCenter\']/table/tr/td/table/tr/td/table/tr[1]/td/table/tr');
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