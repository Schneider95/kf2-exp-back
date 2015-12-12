<?php

// src/Kf2Exp/MainBundle/Services/SteamRequestManager/SteamRequestManager.php

namespace Kf2Exp\AppBundle\Services\SteamRequestManager;

use Kf2Exp\AppBundle\Entity\Stat;
use Kf2Exp\AppBundle\Entity\Achievement;
use Kf2Exp\AppBundle\Entity\PlayerAchievement;
use Kf2Exp\AppBundle\Entity\PlayerStat;
use Kf2Exp\AppBundle\Entity\Player;
use Kf2Exp\AppBundle\Entity\SteamNews;

class SteamRequestManager
{

  protected $em;
  protected $baseUrl = 'http://api.steampowered.com/';
  protected $appId = '232090';

  public function __construct(\Doctrine\ORM\EntityManager $em, $rootDir, $steamApiKey, $maxRequest, $playerCacheSeconds) 
  {
    $this->em = $em;
    $this->rootDir = $rootDir;
    $this->steamApiKey = $steamApiKey;
    $this->maxRequest = $maxRequest;
    $this->playerCacheSeconds = $playerCacheSeconds;
  }

  /**
   * Add inexisting Stats
   * 
   * @param string $json
   * @return array
   */
  public function addInexistingStats($json) 
  {
    if (isset($json["playerstats"]["stats"])) {

      $playerStat = array();
      $existingStat = array();

      $repository = $this->em->getRepository('Kf2ExpAppBundle:Stat');
      $listeStat = $repository->findAll();

      foreach ($listeStat as $s) {
        $existingStat[] = $s->getStatName();
      }

      foreach ($json["playerstats"]["stats"] as $s) {
        $playerStat[] = $s['name'];
      }

      $statToAdd = array_diff($playerStat, $existingStat);

      foreach ($statToAdd as $s) {

        $stat = new Stat();
        $stat->setStatName($s);
        $stat->setVisibleStatName($s);
        $stat->setEnabled(0);

        $this->em->persist($stat);
        $this->em->flush();
      }
    }
  }

  /**
   * Add inexisting Achievements
   * 
   * @param string $json
   * @return array
   */
  public function addInexistingAchievements($json) 
  {
    $playerAchievements = array();
    $existingAchievements = array();

    $repository = $this->em->getRepository('Kf2ExpAppBundle:Achievement');
    $listeAchievements = $repository->findAll();

    foreach ($listeAchievements as $a) {
      $existingAchievements[] = $a->getName();
    }

    foreach ($json["playerstats"]["achievements"] as $a) {
      $playerAchievements[] = $a['name'];
    }

    $achievementToAdd = array_diff($playerAchievements, $existingAchievements);

    foreach ($achievementToAdd as $a) {
      $achievement = new Achievement();
      $achievement->setName($a);
      $achievement->setVisibleName($a);

      $this->em->persist($achievement);
      $this->em->flush();
    }
  }

  /**
   * Check if the player exist on Steam
   * @param string $profileUrl
   * @return int
   */
  public function checkIfPlayerExistInDatabase($profileUrl) 
  {
    $repository = $this->em->getRepository('Kf2ExpAppBundle:Player');

    $p = $repository->findOneByProfileUrl($profileUrl);

    if ($p != null) {
      return $p->getId();
    }

    return 'Player not found in database.';
  }

  /**
   * Check if the player exist on Steam
   * @param string $profileUrl
   * @return int
   */
  public function checkIfPlayerExistOnSteam($profileUrl) 
  {

    $profileUrl = rtrim($profileUrl, '/');
    $profileUrl .= '/?xml=1';

    $curlResult = $this->doCurlRequest($profileUrl);

    if ($curlResult['httpCode'] == 200) {

      $xml = simplexml_load_string($curlResult['content']);

      if (count($xml) != 0) {

        if (isset($xml->steamID64) && !empty($xml->steamID64)) {
          return (string) $xml->steamID64;
        }

        return 'Error while retrieving steamID from Steam API.';
      }

      return 'Error while retrieving steamID from Steam API.';
    }

    return 'Error while request Steam Api for check if the player exist. (Http Error : ' . $curlResult['httpCode'] . ')';
  }

  /**
   * Check if player have game
   * @param string $steamId
   * @return mixed
   */
  public function checkIfPlayerHaveGame($steamId) 
  {

    $jsonUrl = $this->baseUrl . 'ISteamUserStats/GetUserStatsForGame/v0002/?';
    $jsonUrl .= 'appid=' . $this->appId;
    $jsonUrl .= '&key=' . $this->steamApiKey;

    $jsonUrl .= '&steamid=' . $steamId;

    if ($this->checkLimitRequest()) {
      return 'The number of maximum request has been reached.';
    }

    $curlResult = $this->doCurlRequest($jsonUrl);

    if ($curlResult['httpCode'] == 200) {

      $json = json_decode($curlResult['content'], true);

      if ($json == null) {
        return 'Error while parsing Steam API response for retrieving if the player have the game';
      }

      if (!empty($json)) {

        if ((!empty($json['playerstats']['steamID'])) &&
                (!empty($json['playerstats']['achievements'])) &&
                (!empty($json['playerstats']['stats']))) {
          return true;
        } else {
          return false;
        }
      }
    } else {
      return 'Error while request Steam API for retrieving if the player have the game (HTTP Error ' . $curlResult['httpCode'] . ')';
    }
  }

  /**
   * Check if player must be updated
   * @param type $steamId
   * @return boolean
   */
  public function checkIfPlayerMustBeUpdated($steamId) 
  {
    $repository = $this->em->getRepository('Kf2ExpAppBundle:Player');

    $player = $repository->findOneBySteamId($steamId);

    if ($player != null) {
      $timeUpdate = $player->getLastUpdateTime()->getTimestamp();
      $timeNow = time();
      $secondsCache = 60 * 60 * $this->hoursCache;
      $soustraction = $timeNow - $secondsCache;

      if ($soustraction > $timeUpdate) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  /**
   * Do curl request
   * 
   * @param type $url
   * @return array
   */
  public function doCurlRequest($url)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $output = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $nbCurlRequest = file_get_contents($this->rootDir . '/../web/nb_curl.txt');
    $nbCurlRequest++;
    file_put_contents($this->rootDir . '/../web/nb_curl.txt', $nbCurlRequest);

    return array('httpCode' => $code, 'content' => $output);
  }

  /** Get Profile Data
   * @param type $steamId
   * @return
   */
  public function getProfileData($steamId) 
  {
    $jsonUrl = $this->baseUrl . 'ISteamUser/GetPlayerSummaries/v0002/?';
    $jsonUrl .= 'key=' . $this->steamApiKey;
    $jsonUrl .= '&steamids=' . $steamId;

    if ($this->checkLimitRequest()) {
      return 'The number of maximum request has been reached.';
    }

    $curlResult = $this->doCurlRequest($jsonUrl);

    if ($curlResult['httpCode'] == 200) {
      $json = json_decode($curlResult['content'], true);

      if ($json == null) {
        return 'Error while parsing Steam API response for retrieving player profile data.';
      }

      if (isset($json["response"]["players"][0]) == false) {
        return 'Error while parsing Steam API response for retrieving player profile data.';
      }

      $jsonPlayer = $json["response"]["players"][0];

      return $jsonPlayer;
    }

    if ($curlResult['httpCode'] != 0) {
      return 'Error while request Steam API for retrieve player profile (HTTP Error ' . $curlResult['httpCode'] . ')';
    } else {
      return 'Failed to connect to Steam API to retrieve player profile.';
    }
  }


  /** Get latest news from Steam
   * @return
   */
  public function getLatestNewsFromSteam() 
  {
    $jsonUrl = $this->baseUrl . 'ISteamNews/GetNewsForApp/v0002/?';
    $jsonUrl .= 'appid=' . $this->appId;
    $jsonUrl .= '&count=3&maxlength=300&format=json';

    if ($this->checkLimitRequest()) {
      return 'The number of maximum request has been reached.';
    }

    $curlResult = $this->doCurlRequest($jsonUrl);

    if ($curlResult['httpCode'] == 200) {

      $json = json_decode($curlResult['content'], true);

      if ($json == null) {
        return 'Error while parsing Steam API response for retrieving latest news.';
      }

      if (isset($json["appnews"]["newsitems"]) == false) {
        return 'Error while parsing Steam API response for retrieving latest news.';
      }

      $jsonNews = $json["appnews"]["newsitems"];

      return $jsonNews;
    }

    if ($curlResult['httpCode'] != 0) {
      return 'Error while request Steam API for retrieve latest news (HTTP Error ' . $curlResult['httpCode'] . ')';
    } else {
      return 'Failed to connect to Steam API to retrieve latest news.';
    }
  }


  /**
   * Check if we have reached the limit number of request to Steam Api 
   * @return boolean
   */
  public function checkLimitRequest() 
  {
    $nbCurlRequest = file_get_contents($this->rootDir . '/../web/nb_curl.txt');

    if ($nbCurlRequest > $this->maxRequest) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Manage Stats Player
   * 
   * @param string $json
   * @param string $steamId
   * @return array
   */
  public function manageStatsPlayer($json, $steamId) {

    $repository = $this->em->getRepository('Kf2ExpAppBundle:Player');
    $player = $repository->findOneBySteamId($steamId);

    foreach ($json["playerstats"]["stats"] as $playerStatsJson) {

      $repository = $this->em->getRepository('Kf2ExpAppBundle:Stat');
      $stat = $repository->findOneByStatName($playerStatsJson['name']);

      $repository = $this->em
              ->getRepository('Kf2ExpAppBundle:PlayerStat');
      $playerStatBase = $repository->findOneBy(array('player' => $player,
          'stat' => $stat));

      if ($playerStatBase == null) {

        $playerStat = new PlayerStat();
        $playerStat->setPlayer($player);
        $playerStat->setStat($stat);
        $playerStat->setValue($playerStatsJson['value']);
        $this->em->persist($playerStat);
      } else {

        if ($playerStatBase->getValue() != $playerStatsJson['value']) {
          $playerStatBase->setValue($playerStatsJson['value']);
          $this->em->persist($playerStatBase);
        }
      }

      $this->em->flush();
    }
  }

  /**
   * Manage Achievements Player
   * 
   * @param string $json
   * @param string $steamId
   * @return array
   */
  public function manageAchievementsPlayer($json, $steamId) 
  {
    $repository = $this->em->getRepository('Kf2ExpAppBundle:Player');
    $player = $repository->findOneBySteamId($steamId);

    foreach ($json["playerstats"]["achievements"] as $playerAchievementsJson) {

      $achievementRepository = $this->em
              ->getRepository('Kf2ExpAppBundle:Achievement');
      $achievement = $achievementRepository
              ->findOneByName($playerAchievementsJson['name']);

      $playerAchievementRepository = $this->em
              ->getRepository('Kf2ExpAppBundle:PlayerAchievement');

      $playerAchievementBase = $playerAchievementRepository
              ->findOneBy(array('player' => $player,
          'achievement' => $achievement));

      // if the player didn't had this achievement, we had it
      if ($playerAchievementBase == null) {
        $playerAchievement = new PlayerAchievement();
        $playerAchievement->setPlayer($player);
        $playerAchievement->setAchievement($achievement);
        $playerAchievement->setValue($playerAchievementsJson['achieved']);
        $this->em->persist($playerAchievement);
      } else {
        if ($playerAchievementBase->getValue() !=
                $playerAchievementsJson['achieved']) {
          $playerAchievementBase->setValue($playerAchievementsJson['achieved']);
          $this->em->persist($playerAchievementBase);
        }
      }

      $this->em->flush();
    }

    $this->em->persist($player);
    $this->em->flush();
  }

  public function saveUnknownNews($jsonArray) 
  {

    foreach ($jsonArray as $newsFromJson) {

      $repository = $this->em->getRepository('Kf2ExpAppBundle:SteamNews');
      
      $sn = $repository->findOneByGid($newsFromJson['gid']);

      if ($sn == null) {
        $steamNews = new SteamNews();
	$steamNews->setGid($newsFromJson['gid']);
	$steamNews->setTitle($newsFromJson['title']);
	$steamNews->setUrl($newsFromJson['url']);
	$steamNews->setAuthor($newsFromJson['gid']);
	$steamNews->setContents($newsFromJson['contents']);

	$date = new \DateTime();
	$date->setTimestamp($newsFromJson['date']);
	$steamNews->setDate($date);

        $this->em->persist($steamNews);
        $this->em->flush($steamNews);
      }
    }
  }

  public function updateProfileData($steamId) 
  {
    $getProfileDataResult = $this->getProfileData($steamId);

    if (is_array($getProfileDataResult) == false) {
      return $getProfileDataResult;
    }

    $repository = $this->em->getRepository('Kf2ExpAppBundle:Player');
    $p = $repository->findOneBySteamId($steamId);

    if ($p == null) {
      $player = new Player();
    } else {
      $player = $p;
    }

    $jsonPlayer = $getProfileDataResult;

    $player->setSteamId($jsonPlayer["steamid"]);
    $player->setCommunityVisibilityState($jsonPlayer["communityvisibilitystate"]);
    $player->setProfileState(0);
    $player->setSteamName($jsonPlayer["personaname"]);
    $player->setProfileUrl($jsonPlayer["profileurl"]);
    $player->setAvatar($jsonPlayer["avatar"]);
    $player->setAvatarMedium($jsonPlayer["avatarmedium"]);
    $player->setAvatarFull($jsonPlayer["avatarfull"]);
    $player->setPersonaState($jsonPlayer["personastate"]);
    $player->setProfileState($jsonPlayer["profilestate"]);

    if (!empty($jsonPlayer["realname"])) {
      $player->setRealName($jsonPlayer["realname"]);
    } else {
      $player->setRealName('');
    }

    if (!empty($jsonPlayer["primaryclanid"])) {
      $player->setPrimaryClanId($jsonPlayer["primaryclanid"]);
    } else {
      $player->setPrimaryClanId('');
    }

    $date = new \DateTime();
    $date->setTimeStamp($jsonPlayer["lastlogoff"]);
    $player->setLastLogOff($date);

    if (!empty($jsonPlayer["timecreated"])) {
      $date = new \DateTime();
      $date->setTimeStamp($jsonPlayer["timecreated"]);
      $player->setTimeCreated($date);
    } else {
      $date = new \DateTime();
      $date->setTimeStamp(0);
      $player->setTimeCreated($date);
    }

    $date = new \DateTime();
    $date->setTimeStamp(0);
    $player->setLastFriendCheckTime($date);

    $player->setTimePlayed(0);
    $player->setIsRegistering(1);
    $player->setIsCheater(0);

    if (!empty($jsonPlayer["loccountrycode"])) {
      $repository = $this->em
              ->getRepository('Kf2ExpAppBundle:Country');
      $country = $repository
              ->findOneByCountryId($jsonPlayer["loccountrycode"]);

      if ($country != null) {
        $player->setCountry($country);
      }
    }

    if (!empty($jsonPlayer["loccountrycode"]) &&
            !empty($jsonPlayer["locstatecode"])) {

      $repository = $this->em->getRepository('Kf2ExpAppBundle:State');
      $state = $repository
              ->findOneBy(
              array('country' => $country,
                  'stateId' => $jsonPlayer["locstatecode"]));

      if ($country != null) {
        $player->setState($state);
      }
    }

    if (!empty($jsonPlayer["loccountrycode"]) &&
            !empty($jsonPlayer["locstatecode"]) &&
            !empty($jsonPlayer["loccityid"])) {

      $repository = $this->em
              ->getRepository('Kf2ExpAppBundle:City');
      $city = $repository
              ->findOneByCityId($jsonPlayer["loccityid"]);

      if ($country != null) {
        $player->setCity($city);
      }
    }

    if ($p == null) {
      $date = new \DateTime();
      $date->setTimeStamp(0);
      $player->setLastUpdateTime($date);
      $this->em->persist($player);
    } else {
      $this->em->merge($player);
    }

    $this->em->flush();

    return $player->getId();
  }

  /*
   * Update Stats Data
   * @param type $steamId
   * @return 
   */

  public function updateStatsData($steamId) 
  {
    $jsonUrl = $this->baseUrl . 'ISteamUserStats/GetUserStatsForGame/v0002/?';
    $jsonUrl .= 'appid=' . $this->appId;
    $jsonUrl .= '&key=' . $this->steamApiKey . '&steamid=' . $steamId;

    if ($this->checkLimitRequest()) {
      return 'The number of maximum request has been reached';
    }

    $curlResult = $this->doCurlRequest($jsonUrl);

    if ($curlResult['httpCode'] == 200) {

      $json = json_decode($curlResult['content'], true);

      if ($json == null) {
        return 'Error while parsing return from Steam API';
      }

      if (isset($json["playerstats"]["stats"])) {
        $this->addInexistingStats($json);
      } else {
        return 'No stats available for this player.';
      }

      if (isset($json["playerstats"]["achievements"])) {
        $this->addInexistingAchievements($json);
      } else {
        return 'No achievements available for this player.';
      }

      $this->manageStatsPlayer($json, $steamId);
      $this->manageAchievementsPlayer($json, $steamId);

      $repository = $this->em->getRepository('Kf2ExpAppBundle:Player');
      $player = $repository->findOneBySteamId($steamId);

      $this->em->persist($player);
      $this->em->flush();

      return true;
    } else {
      if ($curlResult['httpCode'] != 0) {
        return 'Error while request Steam API for retrieve player stats (HTTP Error ' . $curlResult['httpCode'] . ')';
      } else {
        return 'Failed to connect to Steam API to retrieve player stats.';
      }
    }
  }

  /**
   * Update Time played from Steam API
   * @param type $steamId
   * @return string|boolean
   */
  public function updateTimePlayed($steamId) 
  {
    $jsonUrl = $this->baseUrl . 'IPlayerService/GetOwnedGames/v0001/?';
    $jsonUrl .= 'key=' . $this->steamApiKey;
    $jsonUrl .= '&steamid=' . $steamId . '&format=json';

    if ($this->checkLimitRequest()) {
      return 'The number of maximum request has been reached.';
    }

    $curlResult = $this->doCurlRequest($jsonUrl);

    if ($curlResult['httpCode'] == 200) {
      $json = json_decode($curlResult['content'], true);

      if ($json == null) {
        return 'Error while parsing return from Steam API';
      }

      $repository = $this->em->getRepository('Kf2ExpAppBundle:Player');
      $player = $repository->findOneBySteamId($steamId);
      $timePlayed = 0;

      if (!empty($json["response"]))  {

        $i = -1;

        do {
          $i++;
        } while ($json["response"]["games"][$i]["appid"] != $this->appId &&
        $i < count($json["response"]["games"]));

        if ($json["response"]["games"][$i]["playtime_forever"] != 0) {
          $timePlayed = $json["response"]["games"][$i]["playtime_forever"];
        }
      } else {
        $timePlayed = '0';
      }

      $player->setLastUpdateTime(new \DateTime("now"));
      $player->setTimePlayed($timePlayed);
      $this->em->merge($player);
      $this->em->flush();

      return true;
    } else {
      if ($curlResult['httpCode'] != 0) {
        return 'Error while request Steam API for retrieve player time played (HTTP Error ' . $curlResult['httpCode'] . ')';
      } else {
        return 'Failed to connect to Steam API to retrieve player stats.';
      }
    }
  }

}
