<?php

// src/Kf2Exp/MainBundle/Command/GetCitiesWithPlayersCommand.php

namespace Kf2Exp\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetLatestSteamNewsCommand extends ContainerAwareCommand{

  protected function configure() {
    $this->setName('news:getlatestnewsfromsteam')
            ->setDescription('GetLatestSteamNewsCommand');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {

    $steamRequestManager = $this->getContainer()->get('kf2exp.steamrequestmanager');

    $jsonArray = $steamRequestManager->getLatestNewsFromSteam();

    $steamRequestManager->saveUnknownNews($jsonArray);
  }
}
