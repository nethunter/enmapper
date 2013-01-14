<?php
namespace En\Games\Crawler;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class PastGamesTest extends \PHPUnit_Framework_TestCase
{
    public function testCrawl() 
    {
        $pastGamesHtml = file_get_contents(__DIR__ . '/../../../../stubs/pastgames.html');
        $crawler = new Crawler();
        $crawler->addContent($pastGamesHtml, 'text/html');
        
        $pastGamesCrawl = $this->getMock('En\Games\Crawler\PastGames', 
                array('getCrawler'), array('rusisrael.en.cx'));
        $pastGamesCrawl->expects($this->once())
                ->method('getCrawler')
                ->will($this->returnValue($crawler));

        $gameList = $pastGamesCrawl->getList();
    }
}