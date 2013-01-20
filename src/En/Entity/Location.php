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
     * @ManyToOne(targetEntity="En\Entity\GameLevels", inversedBy="setLevel")
     */
    protected $level;
    /**
     * @Column(type="decimal", scale=10, precision=6)
     */
    protected $long;
    /**
     * @Column(type="decimal", scale=10, precision=6)
     */
    protected $lat;
}
