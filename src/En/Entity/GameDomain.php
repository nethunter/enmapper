<?php
namespace En\Entity;

/**
 * @Entity(repositoryClass="En\Entity\BaseRepository") @Table(name="game_domain")
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
     * @OneToMany(targetEntity="En\Entity\Game", mappedBy="domain")
     */
    protected $games;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->games = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
    
    /**
     * Remove games
     *
     * @param \En\Entity\Game $games
     */
    public function removeGame(\En\Entity\Game $games)
    {
        $this->games->removeElement($games);
    }

    public function getFullLink()
    {
        return 'http://' . $this->getName();
    }
}