<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table("Cities")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\CityRepository")
 */
class City{

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
   * @ORM\Column(name="cityId", type="string", length=11)
   */
  private $cityId;

  /**
   * @var string
   *
   * @ORM\Column(name="cityName", type="string", length=255)
   */
  private $cityName;

  /**
   * @var string
   *
   * @ORM\Column(name="cityLat", type="string", length=255)
   */
  private $cityLat;

  /**
   * @var string
   *
   * @ORM\Column(name="cityLon", type="string", length=255)
   */
  private $cityLon;

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set cityId
   *
   * @param string $cityId
   * @return City
   */
  public function setCityId($cityId) {
    $this->cityId = $cityId;

    return $this;
  }

  /**
   * Get cityId
   *
   * @return string 
   */
  public function getCityId() {
    return $this->cityId;
  }

  /**
   * Set cityName
   *
   * @param string $cityName
   * @return City
   */
  public function setCityName($cityName) {
    $this->cityName = $cityName;

    return $this;
  }

  /**
   * Get cityName
   *
   * @return string 
   */
  public function getCityName() {
    return $this->cityName;
  }

  /**
   * Set cityLat
   *
   * @param string $cityLat
   * @return City
   */
  public function setCityLat($cityLat) {
    $this->cityLat = $cityLat;

    return $this;
  }

  /**
   * Get cityLat
   *
   * @return string 
   */
  public function getCityLat() {
    return $this->cityLat;
  }

  /**
   * Set cityLon
   *
   * @param string $cityLon
   * @return City
   */
  public function setCityLon($cityLon) {
    $this->cityLon = $cityLon;

    return $this;
  }

  /**
   * Get cityLon
   *
   * @return string 
   */
  public function getCityLon() {
    return $this->cityLon;
  }

}
