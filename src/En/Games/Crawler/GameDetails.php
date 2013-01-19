<?php
namespace En\Games\Crawler;

use En\CrawlerClient;

class GameDetails extends CrawlerAbstract
{
    protected $url = '/GameDetails.aspx';
    protected $gameId = null;
    protected $pages = null;
    protected $games = null;
    
    public function __construct($domain, $gameId)
    {
        parent::__construct($domain);
        $this->url .= '?gid=' . $gameId;
    }
    
    public function getData()
    {
        $crawler = $this->getCrawler();
        
        $gameDetailsNode = $crawler->filterXPath(
            '//td[@id=\'tdContentCenter\']'
            . '/table/tr/td/table[3]/tr[2]/td'
        );
        
        $authorSummery = $gameDetailsNode->text();
        return $authorSummery;
    }
}
