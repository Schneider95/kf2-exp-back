<?php

// src/Sdz/BlogBundle/Entity/PlayerStat.php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kf2Exp\AppBundle\Entity\Player;
use Kf2Exp\AppBundle\Entity\Stat;

/**
 * PlayerStat
 *
 * @ORM\Table("PlayerStat")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\PlayerStatRepository")
 */
class PlayerStat{

  /**
   * @ORM\Id
   * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\Player", inversedBy="playerStats")
   * @ORM\JoinColumn(nullable=false)
   */
  private $player;

  /**
   * @ORM\Id
   * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\Stat")
   */
  private $stat;

  /**
   * @ORM\Column(name="value", type="integer")
   */
  private $value;

  public function setPlayer(Player $player) {
    $this->player = $player;
  }

  public function getPlayer() {
    return $this->player;
  }

  public function setStat(Stat $stat) {
    $this->stat = $stat;
  }

  public function getStat() {
    return $this->stat;
  }

  public function setValue($value) {
    $this->value = $value;
  }

  public function getValue() {
    return $this->value;
  }

}
