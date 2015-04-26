<?php

namespace Kf2Exp\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MainController extends Controller{

  /**
   * @Route("/checkIfPlayerExistInDatabase")
   * @Method("GET")
   */
  public function checkIfPlayerExistInDatabaseAction(Request $request) {

    $profileUrl = $request->query->get('profileUrl');

    $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
    $checkIfPlayerExistInDatabaseResponse = $steamRequestManager
            ->checkIfPlayerExistInDatabase($profileUrl);

    return new JsonResponse($checkIfPlayerExistInDatabaseResponse);
  }

  /**
   * @Route("/checkIfPlayerExistOnSteam")
   * @Method("GET")
   */
  public function checkIfPlayerExistOnSteamAction(Request $request) {

    $profileUrl = $request->query->get('profileUrl');

    $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
    $checkIfPlayerExistOnSteamResponse = $steamRequestManager
            ->checkIfPlayerExistOnSteam($profileUrl);

    return new JsonResponse($checkIfPlayerExistOnSteamResponse);
  }

  /**
   * @Route("/checkIfPlayerHaveGame")
   * @Method("GET")
   */
  public function checkIfPlayerHaveGameAction(Request $request) {
    $steamId = $request->query->get('steamId');

    $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
    $checkIfPlayerHaveGameResponse = $steamRequestManager
            ->checkIfPlayerHaveGame($steamId);

    return new JsonResponse($checkIfPlayerHaveGameResponse);
  }

  /**
   * @Route("/getAchievementsList")
   * @Method("GET")
   */
  public function getAchievementsListAction() {
    $em = $this->getDoctrine()->getManager();

    $stats = $em->getRepository('Kf2ExpAppBundle:Achievement')
            ->findAll();

    $serializer = $this->container->get('serializer');
    $json = $serializer->serialize($stats, 'json');
    return new Response($json);
  }

  /**
   * @Route("/getCitiesWithPlayers")
   * @Method("GET")
   */
  public function getCitiesWithPlayersAction(Request $request) {
    $content = file_get_contents('cities.json');
    return new Response($content);
  }

  /**
   * @Route("/getLastUpdatedPlayers")
   * @Method("GET")
   */
  public function getLastUpdatedPlayersAction() {

    $em = $this->getDoctrine()->getManager();
    $repository = $em->getRepository('Kf2ExpAppBundle:Player');

    $players = $repository->findBy(array(), array('lastUpdateTime' => 'desc'), 8, 0);

    $arrayPlayers = array();

    foreach ($players as $p)
    {
      $arrayPlayers[] = array('playerId' => $p->getId(),
          'steamId' => $p->getSteamId(),
          'steamName' => $p->getSteamName(),
          'steamAvatar' => $p->getAvatarMedium(),
          'lastUpdateTime' => $p->getLastUpdateTime()->format('d-m-Y H:i'),
          'timePlayed' => $p->getTimePlayed()
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
  public function getPlayersByCityAction() {
    $em = $this->getDoctrine()->getManager();

    $request = $this->get('request');
    $cityId = $request->query->get('cityId');

    $city = $em->getRepository('Kf2ExpAppBundle:City')->find($cityId);

    $players = $em->getRepository('Kf2ExpAppBundle:Player')
            ->findBy(array('city' => $city));

    $arrayResponse = array();
    $arrayResponse['cityName'] = $city->getCityName();

    $arrayPlayers = array();

    foreach ($players as $p)
    {
      $arrayPlayers[] = array('playerId' => $p->getId(),
          'steamName' => $p->getSteamName());
    }

    $arrayResponse['players'] = $arrayPlayers;

    $response = new JsonResponse($arrayResponse);
    return $response;
  }

  /**
   * @Route("/getPlayersList")
   * @Method("GET")
   */
  public function getPlayersListAction() {
    $em = $this->getDoctrine()->getManager();

    $request = $this->get('request');
    $term = $request->query->get('term');

    $players = $em->getRepository('Kf2ExpAppBundle:Player')
            ->findAll();

    $arrayPlayers = array();

    foreach ($players as $p)
    {
      $arrayPlayers[] = array(
          'playerId' => $p->getId(),
          'steamName' => utf8_encode($p->getSteamName()),
          'steamNameLowercase' => utf8_encode(strtolower($p->getSteamName()))
      );
    }

    $response = new JsonResponse($arrayPlayers);
    return $response;
  }

  /**
   * @Route("/getProfileData")
   * @Method("GET")
   */
  public function getProfileDataAction(Request $request) {
    $steamId = $request->query->get('steamId');

    $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
    $getProfileDataResponse = $steamRequestManager
            ->getProfileData($steamId);

    return new JsonResponse($getProfileDataResponse);
  }

  /**
   * @Route("/getPlayer/{id}")
   * @Method("GET")
   */
  public function getPlayerAction($id) {
    $em = $this->getDoctrine()->getManager();

    $player = $em->getRepository('Kf2ExpAppBundle:Player')
            ->find($id);

    $serializer = $this->container->get('serializer');
    $json = $serializer->serialize($player, 'json');
    return new Response($json);
  }

  /**
   * @Route("/getStatsList")
   * @Method("GET")
   */
  public function getStatsListAction() {
    $em = $this->getDoctrine()->getManager();

    $stats = $em->getRepository('Kf2ExpAppBundle:Stat')
            ->findByEnabled(1);

    $serializer = $this->container->get('serializer');
    $json = $serializer->serialize($stats, 'json');
    return new Response($json);
  }

  /**
   * @Route("/getMapsList")
   * @Method("GET")
   */
  public function getMapsListAction() {
    $em = $this->getDoctrine()->getManager();

    $maps = $em->getRepository('Kf2ExpAppBundle:Achievement')
            ->getMapsList();

    $serializer = $this->container->get('serializer');
    $json = $serializer->serialize($maps, 'json');
    return new Response($json);
  }

  /**
   * @Route("/getStatsRanking")
   * @Method("GET")
   */
  public function getStatsRankingAction() {

    $em = $this->getDoctrine()->getManager();
    $request = $this->get('request');

    $statName = $request->query->get('statName');

    if (empty($statName))
    {

      return new JsonResponse('The GET parameter statName is mandatory and must be a numeric.');
    }

    if ($request->query->get('nbPlayerLoaded') == '' ||
            is_numeric($request->query->get('nbPlayerLoaded')) == false)
    {

      return new JsonResponse('The GET parameter nbPlayerLoaded is mandatory and must be a numeric.');
    }

    if ($request->query->get('nbPlayerToLoad') == '' ||
            is_numeric($request->query->get('nbPlayerToLoad')) == false)
    {

      return new JsonResponse('The GET parameter nbPlayerToLoad is mandatory and must be a numeric.');
    }

    $nbPlayerLoaded = $request->query->get('nbPlayerLoaded');
    $nbPlayerToLoad = $request->query->get('nbPlayerToLoad');

    $playerStats = $em->getRepository('Kf2ExpAppBundle:PlayerStat')
            ->getStatsRanking($statName, $nbPlayerLoaded, $nbPlayerToLoad);

    $response = new JsonResponse();
    $response->setData($playerStats);
    return $response;
  }

  /**
   * @Route("/updateProfileData")
   * @Method("GET")
   */
  public function updateProfileDataAction(Request $request) {
    $steamId = $request->query->get('steamId');

    $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
    $updateProfileDataResponse = $steamRequestManager
            ->updateProfileData($steamId);

    return new JsonResponse($updateProfileDataResponse);
  }

  /**
   * @Route("/updateStatsData")
   * @Method("GET")
   */
  public function updateStatsDataAction(Request $request) {
    $steamId = $request->query->get('steamId');

    $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
    $updateStatsDataResponse = $steamRequestManager
            ->updateStatsData($steamId);

    return new JsonResponse($updateStatsDataResponse);
  }

  /**
   * @Route("/updateTimePlayed")
   * @Method("GET")
   */
  public function updateTimePlayedAction(Request $request) {
    $steamId = $request->query->get('steamId');

    $steamRequestManager = $this->container
            ->get('kf2exp.steamrequestmanager');
    $updateTimePlayedResponse = $steamRequestManager
            ->updateTimePlayed($steamId);

    return new JsonResponse($updateTimePlayedResponse);
  }

}
