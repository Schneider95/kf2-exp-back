<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PlayerRepository extends EntityRepository{

  public function findSteamNameLike($name, $limit = 10) {
    $qb = $this->createQueryBuilder('p');
    $qb->select(array('p.id', 'p.steamName'))
            ->where('p.steamName LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->setMaxResults($limit);

    $arrayPlayers = $qb->getQuery()
            ->getArrayResult();

    return $arrayPlayers;
  }

}
