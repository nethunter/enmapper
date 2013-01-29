<?php
namespace En\Entity;

/**
 * @Entity @Table(name="game")
 */
class Game
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="GameDomain", inversedBy="games")
     */
    protected $domain;

    /**
     *
     * @Column(type="integer")
     */
    protected $extId;

    /**
     * @Column(type="integer")
     */
    protected $num;

    /**
     * @Column(type="string", length=32)
     */
    protected $type;

    /**
     * @Column(type="string")
     */
    protected $link;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Column(type="boolean")
     */
    protected $isIndexed;

    /**
     * @Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @OneToMany(targetEntity="GameLevel", mappedBy="addLevel", cascade={"persist", "remove"})
     */
    protected $levels;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->levels = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set extId
     *
     * @param integer $extId
     * @return Game
     */
    public function setExtId($extId)
    {
        $this->extId = $extId;
    
        return $this;
    }

    /**
     * Get extId
     *
     * @return integer 
     */
    public function getExtId()
    {
        return $this->extId;
    }

    /**
     * Set num
     *
     * @param integer $num
     * @return Game
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
     * Set type
     *
     * @param string $type
     * @return Game
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Game
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
     * Set name
     *
     * @param string $name
     * @return Game
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
     * Set isIndexed
     *
     * @param boolean $isIndexed
     * @return Game
     */
    public function setIsIndexed($isIndexed)
    {
        $this->isIndexed = $isIndexed;
    
        return $this;
    }

    /**
     * Get isIndexed
     *
     * @return boolean 
     */
    public function getIsIndexed()
    {
        return $this->isIndexed;
    }

    /**
     * Set domain
     *
     * @param \En\Entity\GameDomain $domain
     * @return Game
     */
    public function setDomain(\En\Entity\GameDomain $domain = null)
    {
        $this->domain = $domain;
    
        return $this;
    }

    /**
     * Get domain
     *
     * @return \En\Entity\GameDomain 
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Sets the content field
     *
     * @param string $content
     * @return Game
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Returns the game description
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Add levels
     *
     * @param \En\Entity\GameLevel $level
     * @return Game
     */
    public function addLevel(\En\Entity\GameLevel $level)
    {
        $this->levels[] = $level;
        $level->setGame($this);

        return $this;
    }

    /**
     * Remove levels
     *
     * @param \En\Entity\GameLevel $levels
     */
    public function removeLevel(\En\Entity\GameLevel $levels)
    {
        $this->levels->removeElement($levels);
    }

    /**
     * Get levels
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLevels()
    {
        return $this->levels;
    }

    public function __toString()
    {
        $title = '#' . $this->num . ' - ' . $this->name;
        return $title;
    }

    /**
     * Popuplate basic data from details array
     *
     * @param Array $gameDetails
     */
    public function fromArray($gameDetails)
    {
        $this->type = $gameDetails['type'];
        $this->num = $gameDetails['number'];
        $this->name = $gameDetails['title'];
        $this->link = $gameDetails['link'];
        $this->extId = $gameDetails['ext_id'];
        $this->isIndexed = false;

        return $this;
    }
}