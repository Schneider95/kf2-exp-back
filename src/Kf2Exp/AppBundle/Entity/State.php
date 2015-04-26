<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kf2Exp\AppBundle\Entity\Country;

/**
 * State
 *
 * @ORM\Table("States")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\StateRepository")
 */
class State{

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
   * @ORM\Column(name="stateId", type="string", length=3)
   */
  private $stateId;

  /**
   * @var string
   *
   * @ORM\Column(name="stateName", type="string", length=255)
   */
  private $stateName;

  /**
   * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\Country")
   */
  private $country;

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set stateId
   *
   * @param string $stateId
   * @return State
   */
  public function setStateId($stateId) {
    $this->stateId = $stateId;

    return $this;
  }

  /**
   * Get stateId
   *
   * @return string 
   */
  public function getStateId() {
    return $this->stateId;
  }

  /**
   * Set stateName
   *
   * @param string $stateName
   * @return State
   */
  public function setStateName($stateName) {
    $this->stateName = $stateName;

    return $this;
  }

  /**
   * Get stateName
   *
   * @return string 
   */
  public function getStateName() {
    return $this->stateName;
  }

  public function setCountry(Country $country) {
    $this->country = $country;
  }

  public function getCountry() {
    return $this->country;
  }

}
