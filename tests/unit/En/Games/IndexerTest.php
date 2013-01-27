<?php
namespace En\Games;

class IndexerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetGameListUpdated()
    {
        $gameList = new Indexer(null, 'rusisrael.en.cx');
        $gameList->updateGameIndex();
    }
}
