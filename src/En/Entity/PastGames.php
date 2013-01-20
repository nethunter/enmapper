<?php
namespace En\Entity;

use \Doctrine\Common\Collections;

class PastGames {
    protected $gameId = null;
    
    protected $games = null;
    
    public function __construct()
    {
        $this->games = new ArrayCollection();
    }
}
