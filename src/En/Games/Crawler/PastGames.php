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

        if ($page > 1) {
            $this->url .= '?page=' . $page;
        }
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
            $gameDetails = $game->filterXPath('//td[4]/a');

            $number = $game->filter('td span span')->text();
            $link = $gameDetails->attr('href');
            $link_matches = preg_match('/.*gid=(\d+)/', $link, $link_groups);

            if ($link_matches) {
                $ext_id = $link_groups[1];
            }

            $gameData = array(
                'type' => $game->filterXPath('//tr/td[2]/span')->text(),
                'number' => (int)$number,
                'title' => $gameDetails->text(),
                'link' => $link,
                'ext_id' => $ext_id
            );
            
            if ('Quest' == $gameData['type']) {
                $gamesTableDetails[] = $gameData;
            }
        }
        
        return $gamesTableDetails;
    }
}
