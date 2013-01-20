<?php
namespace En\Entity;

/**
 * @Entity @Table(name="game_level")
 */
class GameLevels {
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */    
    protected $id;
    /**
     * @Column(type="integer")
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
     * @Column(type="string")
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
}
