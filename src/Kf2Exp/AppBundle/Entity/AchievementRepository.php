<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AchievementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AchievementRepository extends EntityRepository
{
  public function getAchievementsMapsList()
  {
    $qb = $this->createQueryBuilder('a');
    $qb->select('a')
            ->where('a.enabled = 1')
            ->andWhere('a.achievementMap = 1')
            ->orderBy('a.mapName', 'ASC');

    $achievementMaps = $qb->getQuery()
            ->getResult();

    $results = array();

    foreach ($achievementMaps as $achievementMap) {
      if (array_key_exists($achievementMap->getMapName(), $results) == false) {
        $results[$achievementMap->getMapName()] = array();
      }
    }

    foreach ($achievementMaps as $achievementMap) {
      $results[$achievementMap->getMapName()][$achievementMap->getDifficulty()] = $achievementMap->getAchievementName();
    }

    return $results;
  }
  
  public function getAchievementsPerkDifficultyList()
  {
    $qb = $this->createQueryBuilder('a');
    $qb->select('a')
        ->where('a.enabled = 1')
        ->andWhere('a.perk IS NOT NULL')
        ->orderBy('a.perk');

    $achievementPerkDifficulties = $qb->getQuery()
          ->getResult();

    $results = array();

    foreach ($achievementPerkDifficulties as $achievement) {
      
      if (empty($results[$achievement->getPerk()])) {
        $results[$achievement->getPerk()] = array();
      }

      $results[$achievement->getPerk()][$achievement->getDifficulty()] = $achievement->getAchievementName();
    }

    return $results;
  }
  
  public function getAchievementsClassicList()
  {
    $qb = $this->createQueryBuilder('a');
    $qb->select('a')
            ->where('a.enabled = 1')
            ->andWhere('a.achievementMap = 0')
            ->orderBy('a.visibleAchievementName', 'ASC');

    $achievementsClassic = $qb->getQuery()
            ->getResult();

    $results = array();

    foreach ($achievementsClassic as $achievementClassic) {
      $results[$achievementClassic->getAchievementName()] = $achievementClassic->getVisibleAchievementName();
    }

    return $results;
  }
  
}
