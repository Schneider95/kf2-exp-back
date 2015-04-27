<?php

namespace Kf2Exp\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

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
          'timePlayed' => $p->getTimePlayed()
      );
    }

    $response = new JsonResponse();
    $response->setData($arrayPlayers);
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

    return $response;
  }

  /**
   * @Route("/getPlayersByCity")
   * @Method("GET")
   */
  public function getPlayersByCityAction()
  {
    $em = $this->getDoctrine()->getManager();

    $request = $this->get('request');
    $cityId = $request->query->get('cityId');

    $city = $em->getRepository('Kf2ExpAppBundle:City')->find($cityId);

    $players = $em->getRepository('Kf2ExpAppBundle:Player')
            ->findBy(array('city' => $city));

    $arrayResponse = array();
    $arrayResponse['cityName'] = $city->getCityName();

    $arrayPlayers = array();

    foreach ($players as $p) {
      $arrayPlayers[] = array('playerId' => $p->getId(),
          'steamName' => $p->getSteamName());
    }

    $arrayResponse['players'] = $arrayPlayers;

    $response = new JsonResponse();
    $response->setData($arrayResponse);
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

    return $response;
  }

  /**
   * @Route("/getPlayersList")
   * @Method("GET")
   */
  public function getPlayersListAction()
  {
    $em = $this->getDoctrine()->getManager();

    $request = $this->get('request');
    $term = $request->query->get('term');

    $players = $em->getRepository('Kf2ExpAppBundle:Player')
            ->findAll();

    $arrayPlayers = array();

    foreach ($players as $p) {
      $arrayPlayers[] = array(
          'playerId' => $p->getId(),
          'steamName' => utf8_encode($p->getSteamName()),
          'steamNameLowercase' => utf8_encode(strtolower($p->getSteamName()))
      );
    }

    $response = new JsonResponse();
    $response->setData($arrayPlayers);
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

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
            ->findByEnabled(1);

    $serializer = $this->container->get('serializer');
    $json = $serializer->serialize($stats, 'json');

    $response = new JsonResponse();
    $response->setContent($json);
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

    return $response;
  }

  /**
   * @Route("/getMapsList")
   * @Method("GET")
   */
  public function getMapsListAction()
  {
    $em = $this->getDoctrine()->getManager();

    $maps = $em->getRepository('Kf2ExpAppBundle:Achievement')
            ->getMapsList();

    $serializer = $this->container->get('serializer');
    $json = $serializer->serialize($maps, 'json');

    $response = new JsonResponse();
    $response->setContent($json);
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

    return $response;
  }

  /**
   * @Route("/getStatsRanking")
   * @Method("GET")
   */
  public function getStatsRankingAction()
  {

    $em = $this->getDoctrine()->getManager();
    $request = $this->get('request');

    $statName = $request->query->get('statName');

    $response = new JsonResponse();
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));

    if (empty($statName)) {
      $response->setContent('The GET parameter statName is mandatory and must be a numeric.');
      return $response;
    }

    if ($request->query->get('nbPlayerLoaded') == '' ||
            is_numeric($request->query->get('nbPlayerLoaded')) == false) {

      $response->setContent('The GET parameter nbPlayerLoaded is mandatory and must be a numeric.');
      return $response;
    }

    if ($request->query->get('nbPlayerToLoad') == '' ||
            is_numeric($request->query->get('nbPlayerToLoad')) == false) {

      $response->setContent('The GET parameter nbPlayerToLoad is mandatory and must be a numeric.');
      return $response;
    }

    $nbPlayerLoaded = $request->query->get('nbPlayerLoaded');
    $nbPlayerToLoad = $request->query->get('nbPlayerToLoad');

    $playerStats = $em->getRepository('Kf2ExpAppBundle:PlayerStat')
            ->getStatsRanking($statName, $nbPlayerLoaded, $nbPlayerToLoad);

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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));
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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));
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
    $response->headers->set('Access-Control-Allow-Origin', $this->container->getParameter('front_end_url'));
    $response->setData($updateTimePlayedResponse);

    return $response;
  }

}
