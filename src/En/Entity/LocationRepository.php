<?php
namespace En\Entity;

use Doctrine\ORM\EntityRepository;

class LocationRepository extends BaseRepository
{
    public function findAllAvailableLocations()
    {
        $dql = 'SELECT l, gl, g, gd FROM En\Entity\Location l JOIN l.level gl JOIN gl.game g JOIN g.domain gd';

        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }
}
