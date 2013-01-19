<?php
namespace En\Games\Crawler;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class GameDetailsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAuthorSummaryForGame()
    {
        $gameDetailsHtml = file_get_contents(__DIR__ . '/../../../../stubs/gamedetails.html');
        
        $crawler = new Crawler();
        $crawler->addContent($gameDetailsHtml, 'text/html;charset=utf-8');
        
        $gameDetailsCrawl = $this->getMock(
            'En\Games\Crawler\GameDetails',
            array('getCrawler'),
            array('rusisrael.en.cx', 39352)
        );
        
        $gameDetailsCrawl->expects($this->once())
                ->method('getCrawler')
                ->will($this->returnValue($crawler));

        $authorSummary = $gameDetailsCrawl->getData();
        
        $this->assertNotEmpty($authorSummary);
        $this->assertContains(
            'Игра посвящена музыкальным инструментам. '
            . 'Ну, или тому, что авторы считают музыкальными '
            . 'инструментами.',
            $authorSummary
        );
    }
}
