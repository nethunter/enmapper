<?php
namespace En\Entity;

/**
 * @Entity @Table(name="location")
 */
class Location {
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */    
    protected $id;
    /**
     * @ManyToOne(targetEntity="En\Entity\GameLevel", inversedBy="setLevel")
     */
    protected $gameLevel;
    /**
     * @Column(type="decimal", scale=6, precision=10)
     */
    protected $lng;
    /**
     * @Column(type="decimal", scale=6, precision=10)
     */
    protected $lat;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getGameLevel()
    {
        return $this->gameLevel;
    }

    public function setGameLevel($gameLevel)
    {
        $this->gameLevel = $gameLevel;
        return $this;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }
}
