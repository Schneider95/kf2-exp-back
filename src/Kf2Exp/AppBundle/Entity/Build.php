<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Build.
 *
 * @ORM\Table(name="builds")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Repository\BuildRepository")
 */
class Build
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
     * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\Stat")
     * @ORM\JoinColumn(nullable=true)
     */
    private $stat;

    /**
     * @ORM\Column(name="build_id", type="integer")
     */
    private $buildId;

    /**
     * @var string
     *
     * @ORM\Column(name="composition", type="string", length=255, nullable=true)
     */
    private $composition;

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
     * Set composition.
     *
     * @param string $composition
     *
     * @return Build
     */
    public function setComposition($composition)
    {
        $this->composition = $composition;

        return $this;
    }

    /**
     * Get composition.
     *
     * @return string
     */
    public function getComposition()
    {
        return $this->composition;
    }

    /**
     * Set buildId.
     *
     * @param int $buildId
     *
     * @return Build
     */
    public function setBuildId($buildId)
    {
        $this->buildId = $buildId;

        return $this;
    }

    /**
     * Get buildId.
     *
     * @return int
     */
    public function getBuildId()
    {
        return $this->buildId;
    }

    /**
     * Set stat
     *
     * @param \Kf2Exp\AppBundle\Entity\Stat $stat
     *
     * @return Build
     */
    public function setStat(\Kf2Exp\AppBundle\Entity\Stat $stat = null)
    {
        $this->stat = $stat;

        return $this;
    }

    /**
     * Get stat
     *
     * @return \Kf2Exp\AppBundle\Entity\Stat
     */
    public function getStat()
    {
        return $this->stat;
    }
}
