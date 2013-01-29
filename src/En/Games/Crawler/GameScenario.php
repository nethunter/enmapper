<?php
namespace En\Games\Crawler;

use En\CrawlerClient;

class GameScenario extends CrawlerAbstract
{
    protected $url = '/GameScenario.aspx';
    protected $gameId = null;

    public function __construct($domain, $gameId)
    {
        $this->domain = $domain;
        $this->gameId = $gameId;
        $this->url .= '?gid=' . $gameId;
    }
    
    public function getLevels($crawler)
    {
        $levelTable = $crawler->filterXPath(
            'html/body/table/tr/td[@class=\'white\']/div'
        );
        
        return $levelTable;
    }
    
    public function getData()
    {
        $crawler = $this->getCrawler();
        $levelTable = $this->getLevels($crawler);
        $levelCount = $levelTable->count();
        
        $gameScenario = array();
        for ($i = 0; $i < $levelCount; $i++) {
            $level = $levelTable->eq($i);
            $levelHeader = $level->filter('.Text8');
            $levelContent = $level->filter('.scenarioBlock');

            // Level titles come as 'Level #Number "Level Name"'.
            // Strip the level number.
            $levelTitle = trim($levelHeader->text());
            $levelTitle = preg_replace(
                '/^Level \#\d+ \"(.*)\"/',
                '\1',
                $levelTitle
            );

            // Remove all useless empty spaces from level content
            $levelText = trim($levelContent->text());
            $levelText = preg_replace(
                '/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/',
                '',
                $levelText
            );
            
            $levelData = array(
                'name' => $levelTitle,
                'num' => $levelHeader->filter('a')->attr('name'),
                'content' => $levelText
            );
            
            $gameScenario[] = $levelData;
        }
        
        return $gameScenario;
    }
}
