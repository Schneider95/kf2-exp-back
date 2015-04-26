<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stat
 *
 * @ORM\Table("Stats")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\StatRepository")
 */
class Stat{

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var string
   *
   * @ORM\Column(name="statName", type="string", length=255)
   */
  private $statName;

  /**
   * @var string
   *
   * @ORM\Column(name="visibleStatName", type="string", length=255)
   */
  private $visibleStatName;

  /**
   * @var boolean
   *
   * @ORM\Column(name="enabled", type="boolean")
   */
  private $enabled;

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set statName
   *
   * @param string $statName
   * @return Stat
   */
  public function setStatName($statName) {
    $this->statName = $statName;

    return $this;
  }

  /**
   * Get statName
   *
   * @return string 
   */
  public function getStatName() {
    return $this->statName;
  }

  /**
   * Set visibleStatName
   *
   * @param string $visibleStatName
   * @return Stat
   */
  public function setVisibleStatName($visibleStatName) {
    $this->visibleStatName = $visibleStatName;

    return $this;
  }

  /**
   * Get visibleStatName
   *
   * @return string 
   */
  public function getVisibleStatName() {
    return $this->visibleStatName;
  }

  /**
   * Set enabled
   *
   * @param string $enabled
   * @return Stat
   */
  public function setEnabled($enabled) {
    $this->enabled = $enabled;

    return $this;
  }

  /**
   * Get enabled
   *
   * @return boolean 
   */
  public function getEnabled() {
    return $this->enabled;
  }

}
