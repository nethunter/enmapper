<?php
namespace En\Entity;

/**
 * @Entity(repositoryClass="En\Entity\LocationRepository") @Table(name="location")
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return Location
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    
        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Location
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    
        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set level
     *
     * @param \En\Entity\GameLevel $level
     * @return Location
     */
    public function setLevel(\En\Entity\GameLevel $level = null)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return \En\Entity\GameLevel 
     */
    public function getLevel()
    {
        return $this->level;
    }
}