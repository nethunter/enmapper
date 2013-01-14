<?php
namespace En\Games\Crawler;

use En\CrawlerClient;

class Past {
    protected $_domain = null;
    protected $_url = '/Games.aspx';
    protected $_pages = null;
    protected $_games = null;
    protected $_dev = false;

    public function __construct($domain, $pages = 1, $dev) {
        $this->_domain = $domain;
        $this->_pages = $pages;
        $this->_dev = $dev;
    }
    
    public function getList()
    {
        $request_url = ($this->_dev
                ? 'http://localhost:8181/stubs/Games.html'
                : 'http://' . $this->_domain . $this->_url);

        $client = new CrawlerClient();
        $crawler = $client->request('GET', $request_url);
        
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
        
        print_r($gamesTableDetails);
        
        return $gamesTableDetails;
    }
}