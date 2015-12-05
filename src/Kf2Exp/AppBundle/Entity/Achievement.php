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
   * @ORM\Column(name="achievementName", type="string", length=255)
   */
  private $achievementName;

  /**
   * @var string
   *
   * @ORM\Column(name="visibleAchievementName", type="string", length=255)
   */
  private $visibleAchievementName;

  /**
   * @var string
   *
   * @ORM\Column(name="mapName", type="string", length=255, nullable=true)
   */
  private $mapName;

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
   * @var boolean
   *
   * @ORM\Column(name="achievementMap", type="boolean", nullable=true)
   */
  private $achievementMap;
  
  /**
   * @var boolean
   *
   * @ORM\Column(name="enabled", type="boolean", nullable=true)
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
   * Set achievementName
   *
   * @param string $achievementName
   * @return Achievement
   */
  public function setAchievementName($achievementName) {
    $this->achievementName = $achievementName;

    return $this;
  }

  /**
   * Get achievementName
   *
   * @return string 
   */
  public function getAchievementName() {
    return $this->achievementName;
  }

  /**
   * Set visibleAchievementName
   *
   * @param string $visibleAchievementName
   * @return Achievement
   */
  public function setVisibleAchievementName($visibleAchievementName) {
    $this->visibleAchievementName = $visibleAchievementName;

    return $this;
  }

  /**
   * Get visibleAchievementName
   *
   * @return string 
   */
  public function getVisibleAchievementName() {
    return $this->visibleAchievementName;
  }

  /**
   * Get mapName
   *
   * @return string 
   */
  public function getMapName() {
    return $this->mapName;
  }

  /**
   * Get difficulty
   *
   * @return string 
   */
  public function getDifficulty() {
    return $this->difficulty;
  }


    /**
     * Set mapName
     *
     * @param string $mapName
     * @return Achievement
     */
    public function setMapName($mapName)
    {
        $this->mapName = $mapName;

        return $this;
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
     * Set achievementMap
     *
     * @param boolean $achievementMap
     * @return Achievement
     */
    public function setAchievementMap($achievementMap)
    {
        $this->achievementMap = $achievementMap;

        return $this;
    }

    /**
     * Get achievementMap
     *
     * @return boolean 
     */
    public function getAchievementMap()
    {
        return $this->achievementMap;
    }


    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Achievement
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
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
}
