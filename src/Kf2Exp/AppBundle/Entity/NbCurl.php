<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NbCurl
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\NbCurlRepository")
 */
class NbCurl{

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var integer
   *
   * @ORM\Column(name="nb_request", type="integer")
   */
  private $nbRequest;

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set nbRequest
   *
   * @param integer $nbRequest
   * @return NbCurl
   */
  public function setNbRequest($nbRequest) {
    $this->nbRequest = $nbRequest;

    return $this;
  }

  /**
   * Get nbRequest
   *
   * @return integer 
   */
  public function getNbRequest() {
    return $this->nbRequest;
  }

}
