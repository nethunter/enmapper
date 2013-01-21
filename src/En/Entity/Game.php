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
     * @OneToMany(targetEntity="GameLevel", mappedBy="addGameLevel")
     */
    protected $levels;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return Game
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return GameDomain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param GameDomain $domain
     * @return Game
     */
    public function setDomain(GameDomain $domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtId()
    {
        return $this->extId;
    }

    /**
     * @param $extId
     * @return Game
     */
    public function setExtId($extId)
    {
        $this->extId = $extId;
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
     * @return Game
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return Game
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * @return Game
     */
    public function setLink($link)
    {
        $this->link = $link;
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
     * @param $title
     * @return Game
     */
    public function setName($title)
    {
        $this->name = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsIndexed()
    {
        return $this->isIndexed;
    }

    /**
     * @param $isIndexed
     * @return Game
     */
    public function setIsIndexed($isIndexed)
    {
        $this->isIndexed = $isIndexed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * @param En\Entity\GameLevel $level
     * @return Game
     */
    public function addLevel(En\Entity\GameLevel $level)
    {
        $this->levels[] = $gameLevel;
        return $this;
    }
}
