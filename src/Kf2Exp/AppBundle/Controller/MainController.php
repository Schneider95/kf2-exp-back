<?php

namespace Kf2Exp\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MainController extends Controller
{
    /**
   * @Route("/checkIfPlayerExistInDatabase")
   * @Method("GET")
   */
  public function checkIfPlayerExistInDatabaseAction(Request $request)
  {
      $profileUrl = $request->query->get('profileUrl');

      $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
      $checkIfPlayerExistInDatabaseResponse = $steamRequestManager
            ->checkIfPlayerExistInDatabase($profileUrl);

      $response = new JsonResponse();
      $response->setData($checkIfPlayerExistInDatabaseResponse);

      return $response;
  }

  /**
   * @Route("/checkIfPlayerExistOnSteam")
   * @Method("GET")
   */
  public function checkIfPlayerExistOnSteamAction(Request $request)
  {
      $profileUrl = $request->query->get('profileUrl');

      $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
      $checkIfPlayerExistOnSteamResponse = $steamRequestManager
            ->checkIfPlayerExistOnSteam($profileUrl);

      $response = new JsonResponse();
      $response->setData($checkIfPlayerExistOnSteamResponse);

      return $response;
  }

  /**
   * @Route("/checkIfPlayerHaveGame")
   * @Method("GET")
   */
  public function checkIfPlayerHaveGameAction(Request $request)
  {
      $steamId = $request->query->get('steamId');

      $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
      $checkIfPlayerHaveGameResponse = $steamRequestManager
            ->checkIfPlayerHaveGame($steamId);

      $response = new JsonResponse();
      $response->setData($checkIfPlayerHaveGameResponse);

      return $response;
  }

  /**
   * @Route("/getBuildsList")
   * @Method("GET")
   */
  public function getBuildsListAction()
  {
      $em = $this->getDoctrine()->getManager();

      $builds = $em->getRepository('Kf2ExpAppBundle:Build')
        ->findAll();

      $serializer = $this->container->get('serializer');
      $json = $serializer->serialize($builds, 'json');

      $response = new JsonResponse();
      $response->setContent($json);

      return $response;
  }

  /**
   * @Route("/getAchievementsList")
   * @Method("GET")
   */
  public function getAchievementsListAction()
  {
      $em = $this->getDoctrine()->getManager();

      $stats = $em->getRepository('Kf2ExpAppBundle:Achievement')
        ->findAll();

      $serializer = $this->container->get('serializer');
      $json = $serializer->serialize($stats, 'json');

      $response = new JsonResponse();
      $response->setContent($json);

      return $response;
  }

  /**
   * @Route("/getCitiesWithPlayers")
   * @Method("GET")
   */
  public function getCitiesWithPlayersAction(Request $request)
  {
      $content = file_get_contents('cities.json');

      $response = new JsonResponse();
      $response->setContent($content);

      return $response;
  }

  /**
   * @Route("/getLatestNews")
   * @Method("GET")
   */
  public function getLatestNewsAction()
  {
      $em = $this->getDoctrine()->getManager();

      $news = $em->getRepository('Kf2ExpAppBundle:SteamNews')
            ->findLatestNews(3);

      $serializer = $this->container->get('serializer');
      $json = $serializer->serialize($news, 'json');

      $response = new JsonResponse();
      $response->setContent($json);

      return $response;
  }

  /**
   * @Route("/getLastUpdatedPlayers")
   * @Method("GET")
   */
  public function getLastUpdatedPlayersAction()
  {
      $em = $this->getDoctrine()->getManager();
      $repository = $em->getRepository('Kf2ExpAppBundle:Player');

      $players = $repository->findBy(array(), array('lastUpdateTime' => 'desc'), 8, 0);

      $arrayPlayers = array();

      foreach ($players as $p) {
          $arrayPlayers[] = array('playerId' => $p->getId(),
          'steamId' => $p->getSteamId(),
          'steamName' => $p->getSteamName(),
          'steamAvatar' => $p->getAvatarMedium(),
          'lastUpdateTime' => $p->getLastUpdateTime()->format('d-m-Y H:i'),
          'timePlayed' => $p->getTimePlayed(),
      );
      }

      $response = new JsonResponse();
      $response->setData($arrayPlayers);

      return $response;
  }

  /**
   * @Route("/getPlayersByCity")
   * @Method("GET")
   */
  public function getPlayersByCityAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $cityId = $request->query->get('cityId');

      $city = $em->getRepository('Kf2ExpAppBundle:City')->find($cityId);

      $players = $em->getRepository('Kf2ExpAppBundle:Player')
            ->findBy(array('city' => $city));

      $arrayResponse = array();
      $arrayResponse['cityName'] = $city->getCityName();

      $arrayPlayers = array();

      foreach ($players as $p) {
          $arrayPlayers[] = array('playerId' => $p->getId(),
          'steamName' => $p->getSteamName(), );
      }

      $arrayResponse['players'] = $arrayPlayers;

      $response = new JsonResponse();
      $response->setData($arrayResponse);

      return $response;
  }

  /**
   * @Route("/getPlayersList")
   * @Method("GET")
   */
  public function getPlayersListAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $term = $request->query->get('term');

      $players = $em->getRepository('Kf2ExpAppBundle:Player')
            ->findAll();

      $arrayPlayers = array();

      foreach ($players as $p) {
          $arrayPlayers[] = array(
          'playerId' => $p->getId(),
          'steamName' => utf8_encode($p->getSteamName()),
          'steamNameLowercase' => utf8_encode(strtolower($p->getSteamName())),
      );
      }

      $response = new JsonResponse();
      $response->setData($arrayPlayers);

      return $response;
  }

  /**
   * @Route("/getPlayer/{id}")
   * @Method("GET")
   */
  public function getPlayerAction($id)
  {
      $em = $this->getDoctrine()->getManager();

      $player = $em->getRepository('Kf2ExpAppBundle:Player')
            ->find($id);

      $serializer = $this->container->get('serializer');
      $json = $serializer->serialize($player, 'json');

      $response = new JsonResponse();
      $response->setContent($json);

      return $response;
  }

  /**
   * @Route("/getProfileData")
   * @Method("GET")
   */
  public function getProfileDataAction(Request $request)
  {
      $steamId = $request->query->get('steamId');

      $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
      $getProfileDataResponse = $steamRequestManager
            ->getProfileData($steamId);

      $response = new JsonResponse();
      $response->setData($getProfileDataResponse);

      return $response;
  }

  /**
   * @Route("/getStatsList")
   * @Method("GET")
   */
  public function getStatsListAction()
  {
      $em = $this->getDoctrine()->getManager();

      $stats = $em->getRepository('Kf2ExpAppBundle:Stat')
      ->findBy(array(
        'enabled' => 1,
      ), array(
        'visibleStatName' => 'ASC',
      ));

      $serializer = $this->container->get('serializer');
      $json = $serializer->serialize($stats, 'json');

      $response = new JsonResponse();
      $response->setContent($json);

      return $response;
  }

  /**
   * @Route("/getStatsRanking")
   * @Method("GET")
   */
  public function getStatsRankingAction(Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $statName = $request->query->get('statName');

      $response = new JsonResponse();

      if (empty($statName)) {
          $response->setContent('The GET parameter statName is mandatory and must be a numeric.');

          return $response;
      }

      $playerStats = $em->getRepository('Kf2ExpAppBundle:PlayerStat')
            ->getStatsRanking($statName);

      $response->setData($playerStats);

      return $response;
  }

  /**
   * @Route("/updateProfileData")
   * @Method("GET")
   */
  public function updateProfileDataAction(Request $request)
  {
      $steamId = $request->query->get('steamId');

      $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
      $updateProfileDataResponse = $steamRequestManager
            ->updateProfileData($steamId);

      $response = new JsonResponse();
      $response->setContent($updateProfileDataResponse);

      return $response;
  }

  /**
   * @Route("/updateStatsData")
   * @Method("GET")
   */
  public function updateStatsDataAction(Request $request)
  {
      $steamId = $request->query->get('steamId');

      $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
      $updateStatsDataResponse = $steamRequestManager
            ->updateStatsData($steamId);

      $response = new JsonResponse();
      $response->setData($updateStatsDataResponse);

      return $response;
  }

  /**
   * @Route("/updateTimePlayed")
   * @Method("GET")
   */
  public function updateTimePlayedAction(Request $request)
  {
      $steamId = $request->query->get('steamId');

      $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
      $updateTimePlayedResponse = $steamRequestManager
            ->updateTimePlayed($steamId);

      $response = new JsonResponse();
      $response->setData($updateTimePlayedResponse);

      return $response;
  }
}
