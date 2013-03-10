<?php
namespace En\Entity;

use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository
{
    public function getCount()
    {
        $dql = 'SELECT COUNT(e) FROM ' . $this->getEntityName() . ' e';

        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getSingleScalarResult();
    }
}