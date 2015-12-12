<?php

namespace Kf2Exp\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Kf2Exp\AppBundle\Entity\Country;
use Kf2Exp\AppBundle\Entity\State;
use Kf2Exp\AppBundle\Entity\City;
use Kf2Exp\AppBundle\Entity\PlayerAchievement;

/**
 * Player
 *
 * @ORM\Table("Players")
 * @ORM\Entity(repositoryClass="Kf2Exp\AppBundle\Entity\PlayerRepository")
 */
class Player{

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=17)
   */
  private $steamId;

  /**
   * @ORM\Column(type="string", length=100)
   */
  private $steamName;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $profileUrl;

  /**
   * @ORM\Column(type="integer", length=10)
   */
  private $communityVisibilityState;

  /**
   * @ORM\Column(type="integer", length=15)
   */
  private $timePlayed;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $avatar;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $avatarMedium;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $avatarFull;

  /**
   * @ORM\Column(type="datetime")
   */
  private $lastUpdateTime;

  /**
   * @ORM\Column(type="boolean")
   */
  private $isRegistering;

  /**
   * @ORM\Column(type="integer", length=2)
   */
  private $profileState;

  /**
   * @ORM\Column(type="datetime")
   */
  private $lastLogOff;

  /**
   * @ORM\Column(type="integer", length=2)
   */
  private $personaState;

  /**
   * @ORM\Column(type="datetime")
   */
  private $timeCreated;

  /**
   * @ORM\Column(type="string", length=100)
   */
  private $primaryClanId;

  /**
   * @ORM\Column(type="boolean")
   */
  private $isCheater;

  /**
   * @ORM\Column(type="datetime")
   */
  private $lastFriendCheckTime;

  /**
   * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\Country")
   */
  private $country;

  /**
   * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\State")
   */
  private $state;

  /**
   * @ORM\ManyToOne(targetEntity="Kf2Exp\AppBundle\Entity\City")
   */
  private $city;

  /**
   * @ORM\OneToMany(targetEntity="Kf2Exp\AppBundle\Entity\PlayerStat", mappedBy="player")
   */
  private $playerStats;

  /**
   * @ORM\OneToMany(targetEntity="Kf2Exp\AppBundle\Entity\PlayerAchievement", mappedBy="player")
   */
  private $playerAchievements;
  static public $cacheLimit = 900; // quinze minutes 

  /**
   * Get id
   *
   * @return integer 
   */

  public function getId() {
    return $this->id;
  }

  public function setSteamId($steamId) {
    $this->steamId = $steamId;
    return $this;
  }

  public function getSteamId() {
    return $this->steamId;
  }

  public function setSteamName($steamName) {
    $this->steamName = $steamName;
    return $this;
  }

  public function getSteamName() {
    return $this->steamName;
  }

  public function setProfileUrl($profileUrl) {
    $this->profileUrl = $profileUrl;
    return $this;
  }

  public function getProfileURL() {
    return $this->profileURL;
  }

  public function setCommunityVisibilityState($communityVisibilityState) {
    $this->communityVisibilityState = $communityVisibilityState;
    return $this;
  }

  public function getCommunityVisibilityState() {
    return $this->communityVisibilityState;
  }

  public function setTimePlayed($timePlayed) {
    $this->timePlayed = $timePlayed;
    return $this;
  }

  public function getTimePlayed() {
    return $this->timePlayed;
  }

  public function setAvatar($avatar) {
    $this->avatar = $avatar;
    return $this;
  }

  public function getAvatar() {
    return $this->avatar;
  }

  public function setAvatarMedium($avatarMedium) {
    $this->avatarMedium = $avatarMedium;
    return $this;
  }

  public function getAvatarMedium() {
    return $this->avatarMedium;
  }

  public function setAvatarFull($avatarFull) {
    $this->avatarFull = $avatarFull;
    return $this;
  }

  public function getAvatarFull() {
    return $this->avatarFull;
  }

  public function setLastUpdateTime($lastUpdateTime) {
    $this->lastUpdateTime = $lastUpdateTime;
    return $this;
  }

  public function getLastUpdateTime() {
    return $this->lastUpdateTime;
  }

  public function getLastUpdateTimeTimeStamp() {
    return $this->lastUpdateTime->getTimestamp();
  }

  public function setIsRegistering($isRegistering) {
    $this->isRegistering = $isRegistering;
    return $this;
  }

  public function getIsRegistering() {
    return $this->isRegistering;
  }

  public function setProfileState($profileState) {
    $this->profileState = $profileState;
    return $this;
  }

  public function getProfileState() {
    return $this->profileState;
  }

  public function setPersonaState($personaState) {
    $this->personaState = $personaState;
    return $this;
  }

  public function getPersonaState() {
    return $this->personaState;
  }

  public function setRealName($realName) {
    $this->realName = $realName;
    return $this;
  }

  public function getRealName() {
    return $this->realName;
  }

  public function setPrimaryClanId($primaryClanId) {
    $this->primaryClanId = $primaryClanId;
    return $this;
  }

  public function getPrimaryClanId() {
    return $this->primaryClanId;
  }

  public function setTimeCreated($timeCreated) {
    $this->timeCreated = $timeCreated;
    return $this;
  }

  public function getTimeCreated() {
    return $this->timeCreated;
  }

  /**
   * Constructor
   */
  public function __construct() {
    $this->stats = new ArrayCollection();
  }

  /**
   * Set lastLogOff
   *
   * @param \DateTime $lastLogOff
   * @return Player
   */
  public function setLastLogOff($lastLogOff) {
    $this->lastLogOff = $lastLogOff;

    return $this;
  }

  /**
   * Get lastLogOff
   *
   * @return \DateTime 
   */
  public function getLastLogOff() {
    return $this->lastLogOff;
  }

  /**
   * Set isCheater
   *
   * @param boolean $isCheater
   * @return Player
   */
  public function setIsCheater($isCheater) {
    $this->isCheater = $isCheater;

    return $this;
  }

  /**
   * Get isCheater
   *
   * @return boolean 
   */
  public function getIsCheater() {
    return $this->isCheater;
  }

  /**
   * Set lastFriendCheckTime
   *
   * @param \DateTime $lastFriendCheckTime
   * @return Player
   */
  public function setLastFriendCheckTime($lastFriendCheckTime) {
    $this->lastFriendCheckTime = $lastFriendCheckTime;
    return $this;
  }

  /**
   * Get lastFriendCheckTime
   *
   * @return \DateTime 
   */
  public function getLastFriendCheckTime() {
    return $this->lastFriendCheckTime;
  }

  public function setNbAchievements($nbAchievements) {
    $this->nbAchievements = $nbAchievements;
    return $this;
  }

  public function getNbAchievements() {
    return $this->nbAchievements;
  }

  public function setCity(City $city) {
    $this->city = $city;
  }

  public function getCity() {
    return $this->city;
  }

  public function setCountry(Country $country) {
    $this->country = $country;
  }

  public function getCountry() {
    return $this->country;
  }

  public function setState(State $state) {
    $this->state = $state;
  }

  public function getState() {
    return $this->state;
  }

  public function getPlayerStats() {
    return $this->playerStats;
  }

  public function addPlayerAchievement(PlayerAchievement $playerAchievement) {
    $this->playerAchievements[] = $playerAchievement;
    return $this;
  }

  public function removePlayerAchievement(PlayerAchievement $playerAchievement) {
    $this->playerAchievements->removeElement($playerAchievement);
  }

  public function getPlayerAchievements() {
    return $this->playerAchievements;
  }

  /**
   * Add playerStats
   *
   * @param \Kf2Exp\AppBundle\Entity\PlayerStat $playerStats
   * @return Player
   */
  public function addPlayerStat(\Kf2Exp\AppBundle\Entity\PlayerStat $playerStats)
  {
      $this->playerStats[] = $playerStats;

      return $this;
  }

  /**
   * Remove playerStats
   *
   * @param \Kf2Exp\AppBundle\Entity\PlayerStat $playerStats
   */
  public function removePlayerStat(\Kf2Exp\AppBundle\Entity\PlayerStat $playerStats)
  {
      $this->playerStats->removeElement($playerStats);
  }
}
