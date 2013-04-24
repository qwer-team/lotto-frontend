<?php

namespace  Qwer\LottoFrontendBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CountryService extends ContainerAware
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;
    /**
     * 
     * @return type
     */
    public function getCountries()
    {
        /*$this->em = $this->container->get("doctrine.orm.entity_manager");
        $countries = $this->em->getRepository('QwerLottoFrontendBundle:Country\Country')->findAll();
       
        
        return $countries;*/
    }


}