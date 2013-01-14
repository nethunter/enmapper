<?php
namespace En\Games;

use Goutte\Client;

class Past {
    protected $_domain = null;
    protected $_url = '/Games.aspx';
    protected $_pages = null;
    protected $_games = null;
    protected $_debug = false;

    public function __construct($domain, $pages = 1, $debug) {
        $this->_domain = $domain;
        $this->_pages = $pages;
        $this->_debug = $debug;
    }
    
    public function getList()
    {
        $client = new Client();
        $client->getClient()->setConfig(array(
            'curl.options' => array(
                CURLOPT_CONNECTTIMEOUT => 60,
                CURLOPT_TIMEOUT => 60,
            ),
        ));
        
        $request_url = ($this->_debug
                ? 'http://localhost:8181/stubs/Games.html'
                : 'http://' . $this->_domain . $this->_url);
        
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