<?php
namespace En\Entity;

/**
 * @Entity @Table(name="game_level")
 */
class GameLevel {
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */    
    protected $id;
    /**
     * @ManyToOne(targetEntity="Game", inversedBy="levels")
     */
    protected $game;
    /**
     * @Column(type="integer")
     */    
    protected $num;
    /**
     * @Column(type="string")
     */    
    protected $name;
    /**
     * @Column(type="string", nullable=true)
     */    
    protected $link;
    /**
     * @Column(type="text")
     */    
    protected $content;

    /**
     * @OneToMany(targetEntity="Location", mappedBy="addLocation")
     */
    protected $locations;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return GameLevel
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     * @return GameLevel
     */
    public function setGame($game)
    {
        $this->game = $game;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * @param $num
     * @return GameLevel
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return GameLevel
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param $link
     * @return GameLevel
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $content
     * @return GameLevel
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return Location
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param Location $location
     * @return GameLevel
     */
    public function addLocation($location)
    {
        $this->locations[] = $location;
        return $this;
    }

    
}
