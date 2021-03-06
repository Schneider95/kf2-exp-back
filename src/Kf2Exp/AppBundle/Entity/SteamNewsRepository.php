<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SteamNewsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SteamNewsRepository extends EntityRepository
{
  public function findLatestNews($limit = 3) {
   
    $qb = $this->createQueryBuilder('n');
    $qb->select('n')
      ->orderBy('n.date', 'DESC')
      ->setFirstResult(0)
      ->setMaxResults($limit);

    $arrayNews = $qb->getQuery()
            ->getArrayResult();

    return $arrayNews;
  }
}
