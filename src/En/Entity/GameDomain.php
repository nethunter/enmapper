<?php
namespace En\Entity;

/**
 * @Entity @Table(name="game_domain")
 */
class GameDomain
{
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string")
     */
    protected $name;
    /**
     * @OneToMany(targetEntity="En\Entity\Game", mappedBy="addGame")
     */
    protected $games;
}