<?php

namespace Kf2Exp\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCitiesWithPlayersCommand extends ContainerAwareCommand{

  protected function configure() {
    $this->setName('stats:getcitieswithplayers')
            ->setDescription('GetCitiesWithPlayersCommand');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();

    $sql = "SELECT      COUNT(p.steamId) as number_player, c.id, c.cityName, 
                        c.cityLat, c.cityLon
            FROM        Players p, Cities c
            WHERE       c.id      =   p.city_id
            AND         p.city_id <>  ''
            GROUP BY    c.cityName, c.cityLat, c.cityLon";

    $stmt = $em->getConnection()->prepare($sql);
    $stmt->execute();
    $fetch = $stmt->fetchAll();
    $json = json_encode($fetch);

    file_put_contents($this->getContainer()->getParameter('cities_json_location') . 'cities.json', $json);

    return $json;
  }

}
