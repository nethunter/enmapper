<?php
namespace En\Games\Crawler;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class GameScenarioTest extends \PHPUnit_Framework_TestCase
{
    public function testGetScenarioDetailsForGame()
    {
        $gameScenarioHtml = file_get_contents(__DIR__ . '/../../../../stubs/gamedetails.html');
        
        $crawler = new Crawler();
        $crawler->addContent($gameScenarioHtml, 'text/html;charset=utf-8');
        
        $gameScenarioCrawl = $this->getMock(
            'En\Games\Crawler\GameDetails',
            array('getCrawler'),
            array('rusisrael.en.cx', 39352)
        );
        
        $gameScenarioCrawl->expects($this->once())
                ->method('getCrawler')
                ->will($this->returnValue($crawler));

        $gameScenario = $gameScenarioCrawl->getData();
        
        
        $this->assertNotEmpty($authorSummary);
        $this->assertContains(
            'Игра посвящена музыкальным инструментам. '
            . 'Ну, или тому, что авторы считают музыкальными '
            . 'инструментами.',
            $authorSummary
        );
    }
}
