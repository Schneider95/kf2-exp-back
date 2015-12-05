<?php

namespace Kf2Exp\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseListener
{
  public function __construct($frontEndUrl) 
  {
    $this->frontEndUrl = $frontEndUrl;
  }
  
  public function onKernelResponse(FilterResponseEvent $event)
  {
      $event->getResponse()->headers->set('Access-Control-Allow-Origin', $this->frontEndUrl);
  }
}