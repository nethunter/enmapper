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
     * @ManyToOne(targetEntity="En\Entity\Game", inversedBy="setGame")
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
     * @OneToMany(targetEntity="En\Entity\Location", mappedBy="addLocation")
     */
    protected $locations;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setGame($game)
    {
        $this->game = $game;
        return $this;
    }

    public function getNum()
    {
        return $this->num;
    }

    public function setNum($num)
    {
        $this->num = $num;
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

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function addLocation($location)
    {
        $this->locations[] = $location;
        return $this;
    }

    
}
