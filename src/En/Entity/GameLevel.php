<?php
namespace En\Entity;

/**
 * @Entity(repositoryClass="En\Entity\BaseRepository") @Table(name="game_level")
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
     * @OneToMany(targetEntity="Location", mappedBy="addLocation", cascade={"persist", "remove"})
     */
    protected $locations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->locations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set num
     *
     * @param integer $num
     * @return GameLevel
     */
    public function setNum($num)
    {
        $this->num = $num;
    
        return $this;
    }

    /**
     * Get num
     *
     * @return integer 
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return GameLevel
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return GameLevel
     */
    public function setLink($link)
    {
        $this->link = $link;
    
        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return GameLevel
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set game
     *
     * @param \En\Entity\Game $game
     * @return GameLevel
     */
    public function setGame(\En\Entity\Game $game = null)
    {
        $this->game = $game;
    
        return $this;
    }

    /**
     * Get game
     *
     * @return \En\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Add locations
     *
     * @param \En\Entity\Location $location
     * @return GameLevel
     */
    public function addLocation(\En\Entity\Location $location)
    {
        $this->locations[] = $location;
        $location->setLevel($this);

        return $this;
    }

    /**
     * Remove locations
     *
     * @param \En\Entity\Location $locations
     */
    public function removeLocation(\En\Entity\Location $locations)
    {
        $this->locations->removeElement($locations);
    }

    /**
     * Get locations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * If the level's number is above 0, return it as part of the title.
     * Otherwise - just the title.
     *
     * @return string A full text string of the level
     */
    public function __toString()
    {
        if ($this->num) {
            return 'Level #' . $this->num . ' - "' . $this->name . '"';
        } else {
            return $this->name;
        }
    }

    public function getFullLink()
    {
        $link = 'http://' . $this->getGame()->getDomain()->getFullLink();
        $link .= $this->link;

        return $link;
    }
}