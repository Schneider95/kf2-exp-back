<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stat.
 *
 * @ORM\Table("Stats")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\StatRepository")
 */
class Stat
{
    /**
   * @var int
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
   * @var bool
   *
   * @ORM\Column(name="enabled", type="boolean")
   */
  private $enabled;

  /**
   * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\Perk")
   * @ORM\JoinColumn(nullable=true)
   */
  private $perk;

  /**
   * @var bool
   *
   * @ORM\Column(name="is_xp", type="boolean")
   */
  private $isXp;

  /**
   * Get id.
   *
   * @return int 
   */
  public function getId()
  {
      return $this->id;
  }

  /**
   * Set statName.
   *
   * @param string $statName
   *
   * @return Stat
   */
  public function setStatName($statName)
  {
      $this->statName = $statName;

      return $this;
  }

  /**
   * Get statName.
   *
   * @return string 
   */
  public function getStatName()
  {
      return $this->statName;
  }

  /**
   * Set visibleStatName.
   *
   * @param string $visibleStatName
   *
   * @return Stat
   */
  public function setVisibleStatName($visibleStatName)
  {
      $this->visibleStatName = $visibleStatName;

      return $this;
  }

  /**
   * Get visibleStatName.
   *
   * @return string 
   */
  public function getVisibleStatName()
  {
      return $this->visibleStatName;
  }

  /**
   * Set enabled.
   *
   * @param string $enabled
   *
   * @return Stat
   */
  public function setEnabled($enabled)
  {
      $this->enabled = $enabled;

      return $this;
  }

  /**
   * Get enabled.
   *
   * @return bool 
   */
  public function getEnabled()
  {
      return $this->enabled;
  }

    /**
     * Set perk.
     *
     * @param \Kf2Exp\AppBundle\Entity\Perk $perk
     *
     * @return Stat
     */
    public function setPerk(\Kf2Exp\AppBundle\Entity\Perk $perk = null)
    {
        $this->perk = $perk;

        return $this;
    }

    /**
     * Get perk.
     *
     * @return \Kf2Exp\AppBundle\Entity\Perk
     */
    public function getPerk()
    {
        return $this->perk;
    }

    /**
     * Set isXp.
     *
     * @param bool $isXp
     *
     * @return Stat
     */
    public function setIsXp($isXp)
    {
        $this->isXp = $isXp;

        return $this;
    }

    /**
     * Get isXp.
     *
     * @return bool
     */
    public function getIsXp()
    {
        return $this->isXp;
    }
}
