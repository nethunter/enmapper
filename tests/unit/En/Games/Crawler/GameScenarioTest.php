<?php
namespace En\Games\Crawler;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class GameScenarioTest extends \PHPUnit_Framework_TestCase
{
    public function testGetScenarioDetailsForGame()
    {
        $gameScenarioHtml = file_get_contents(__DIR__ . '/../../../../stubs/gamescenario.html');
        
        $crawler = new Crawler();
        $crawler->addContent($gameScenarioHtml, 'text/html;charset=utf-8');
        
        $gameScenarioCrawl = $this->getMock(
            'En\Games\Crawler\GameScenario',
            array('getCrawler'),
            array('rusisrael.en.cx', 39352)
        );
        
        $gameScenarioCrawl->expects($this->once())
                ->method('getCrawler')
                ->will($this->returnValue($crawler));

        $gameScenario = $gameScenarioCrawl->getData();
        
        $this->assertNotEmpty($gameScenario);
        $this->assertCount(24, $gameScenario);
        $this->assertContains(
            array(
                'name' => 'Level #24 "Последняя заглушка"',
                'num' => 24,
                'content' => "Autopass: in 5 minutes\t\t\t\t\t\tTask for "
                    . "allНу что ж, молодцы!\rБанально надеемся, что вам "
                    . "тупо понравилось! :)\rЖдем вас в баре Пророк-Сайгон, "
                    . "ул. Левонтин 11, Тель Авив."
            ),
            $gameScenario
        );
                
    }
}
