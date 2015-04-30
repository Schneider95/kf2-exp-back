<?php

namespace Kf2Exp\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetNbCurlCommand extends ContainerAwareCommand{

  protected function configure() {
    $this->setName('stats:resetnbcurl')->setDescription('Reset Nb Curl.');
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    file_put_contents($this->getContainer()->getParameter('nb_curl_txt_location') . 'nb_curl.txt', '0');
  }

}

?>