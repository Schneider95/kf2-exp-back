<?php

// src/Kf2Exp/AppBundle/Entity/PlayerAchievement.php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kf2Exp\AppBundle\Entity\Player;
use Kf2Exp\AppBundle\Entity\Achievement;

/**
 * PlayerAchievement
 *
 * @ORM\Table("PlayerAchievement")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\PlayerAchievementRepository")
 */
class PlayerAchievement{

  /**
   * @ORM\Id
   * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\Player", inversedBy="playerAchievements")
   * @ORM\JoinColumn(nullable=false)
   */
  private $player;

  /**
   * @ORM\Id
   * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\Achievement")
   */
  private $achievement;

  /**
   * @ORM\Column()
   */
  private $value;

  public function setPlayer(Player $player) {
    $this->player = $player;
  }

  public function getPlayer() {
    return $this->player;
  }

  // Getter et setter pour l'entité Competence
  public function setAchievement(Achievement $achievement) {
    $this->achievement = $achievement;
  }

  public function getAchievement() {
    return $this->achievement;
  }

  // On définit le getter/setter de l'attribut « niveau »
  public function setValue($value) {
    $this->value = $value;
  }

  public function getValue() {
    return $this->value;
  }

}
