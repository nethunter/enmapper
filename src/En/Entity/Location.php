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
     * @ManyToOne(targetEntity="GameLevel", inversedBy="locations")
     */
    protected $level = null;
    /**
     * @Column(type="decimal", scale=6, precision=10)
     */
    protected $lng;
    /**
     * @Column(type="decimal", scale=6, precision=10)
     */
    protected $lat;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return Location
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return GameLevel
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param GameLevel $level
     * @return Location
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     * @return Location
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     * @return Location
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }
}
