<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table("Countries")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\CountryRepository")
 */
class Country{

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
   * @ORM\Column(name="countryId", type="string", length=3)
   */
  private $countryId;

  /**
   * @var string
   *
   * @ORM\Column(name="countryName", type="string", length=255)
   */
  private $countryName;

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set countryId
   *
   * @param string $countryId
   * @return Country
   */
  public function setCountryId($countryId) {
    $this->countryId = $countryId;

    return $this;
  }

  /**
   * Get countryId
   *
   * @return string 
   */
  public function getCountryId() {
    return $this->countryId;
  }

  /**
   * Set countryName
   *
   * @param string $countryName
   * @return Country
   */
  public function setCountryName($countryName) {
    $this->countryName = $countryName;

    return $this;
  }

  /**
   * Get countryName
   *
   * @return string 
   */
  public function getCountryName() {
    return $this->countryName;
  }

}
