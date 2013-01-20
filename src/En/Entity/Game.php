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
    protected $indexed;
    /**
     * @OneToMany(targetEntity="En\Entity\GameLevels", mappedBy="addLevel")
     */
    protected $gameLevels;
}
