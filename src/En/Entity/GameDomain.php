<?php
namespace En\Entity;

/**
 * @Entity @Table(name="game_domain")
 */
class GameDomain
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string")
     */
    protected $name;
    /**
     * @OneToMany(targetEntity="Game", mappedBy="addGame")
     */
    protected $games;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getGames()
    {
        return $this->games;
    }

    public function addGame($game)
    {
        $this->games[] = $game;
        return $this;
    }

    
}