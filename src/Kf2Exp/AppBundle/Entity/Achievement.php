<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Achievement
 *
 * @ORM\Table("Achievements")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\AchievementRepository")
 */
class Achievement{

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
   * @ORM\Column(name="name", type="string", length=255)
   */
  private $name;

  /**
   * @var string
   *
   * @ORM\Column(name="visibleName", type="string", length=255)
   */
  private $visibleName;

  /**
   * @var collectible
   *
   * @ORM\Column(name="collectible", type="boolean", nullable=true)
   */
  private $collectible;
  
  /**
   * @var string
   *
   * @ORM\Column(name="map", type="string", length=255, nullable=true)
   */
  private $map;

  /**
   * @var string
   *
   * @ORM\Column(name="perk", type="string", nullable=true)
   */
  private $perk;
  
  /**
   * @var string
   *
   * @ORM\Column(name="difficulty", type="string", length=255, nullable=true)
   */
  private $difficulty;

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

    /**
     * Set name
     *
     * @param string $name
     * @return Achievement
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set visibleName
     *
     * @param string $visibleName
     * @return Achievement
     */
    public function setVisibleName($visibleName)
    {
        $this->visibleName = $visibleName;

        return $this;
    }

    /**
     * Get visibleName
     *
     * @return string 
     */
    public function getVisibleName()
    {
        return $this->visibleName;
    }

    /**
     * Set collectible
     *
     * @param boolean $collectible
     * @return Achievement
     */
    public function setCollectible($collectible)
    {
        $this->collectible = $collectible;

        return $this;
    }

    /**
     * Get collectible
     *
     * @return boolean 
     */
    public function getCollectible()
    {
        return $this->collectible;
    }

    /**
     * Set map
     *
     * @param string $map
     * @return Achievement
     */
    public function setMap($map)
    {
        $this->map = $map;

        return $this;
    }

    /**
     * Get map
     *
     * @return string 
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * Set perk
     *
     * @param string $perk
     * @return Achievement
     */
    public function setPerk($perk)
    {
        $this->perk = $perk;

        return $this;
    }

    /**
     * Get perk
     *
     * @return string 
     */
    public function getPerk()
    {
        return $this->perk;
    }

    /**
     * Set difficulty
     *
     * @param string $difficulty
     * @return Achievement
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Get difficulty
     *
     * @return string 
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }
}
