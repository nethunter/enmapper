<?php
namespace En\Games\Crawler;

use Symfony\Component\DomCrawler\Crawler as Crawler;

class PastGamesTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOnePageOfPastGamesFilteredForQuest()
    {
        $pastGamesHtml = file_get_contents(__DIR__ . '/../../../../stubs/pastgames.html');
        
        $crawler = new Crawler();
        $crawler->addContent($pastGamesHtml, 'text/html;charset=utf-8');
        
        $pastGamesCrawl = $this->getMock(
            'En\Games\Crawler\PastGames',
            array('getCrawler'),
            array('rusisrael.en.cx')
        );
        $pastGamesCrawl->expects($this->once())
                ->method('getCrawler')
                ->will($this->returnValue($crawler));

        $gameList = $pastGamesCrawl->getData();

        $this->assertNotEmpty($gameList);
        $this->assertCount(8, $gameList);
        $this->assertEquals(
            $gameList[0],
            array(
                'type' => 'Quest',
                'number' => 85,
                'title' => 'Эта музыка будет вечной',
                'ext_id' => 39352,
                'link' => '/GameDetails.aspx?gid=39352'
            )
        );
    }
}
