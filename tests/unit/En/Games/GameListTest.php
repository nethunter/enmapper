<?php
namespace En\Games;

class GameListTest extends Web
{

    public function testGetGameListUpdated()
    {
        $gameList = new GameList(null, 'rusisrael.en.cx');
        $gameList->updateGameIndex();
    }
}
