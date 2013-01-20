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
     * @ManyToOne(targetEntity="En\Entity\GameDomain", inversedBy="setDomain")
     */
    protected $domain;
    /**
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
    protected $title;
    /**
     * @Column(type="boolean")
     */
    protected $isIndexed;
    /**
     * @OneToMany(targetEntity="En\Entity\GameLevel", mappedBy="addGameLevel")
     */
    protected $gameLevels;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    public function getExtId()
    {
        return $this->extId;
    }

    public function setExtId($extId)
    {
        $this->extId = $extId;
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

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getIsIndexed()
    {
        return $this->isIndexed;
    }

    public function setIsIndexed($isIndexed)
    {
        $this->isIndexed = $isIndexed;
        return $this;
    }

    public function getGameLevels()
    {
        return $this->gameLevels;
    }

    public function addGameLevel(En\Entity\GameLevel $gameLevel)
    {
        $this->gameLevels[] = $gameLevel;
        return $this;
    }
}
