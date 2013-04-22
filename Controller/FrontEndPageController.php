<?php

namespace Qwer\LottoFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FrontEndPageController extends Controller
{
    /**
     * @Route("/hello/lotto/undle")
     * @Template()
     */
    public function indexAction()
    {
//        $countries = $this->container->get("client.country_service");
//        $countries =$countries->getCountries();
        
        $em=$this->container->get('doctrine');
        $rateFormules = $em->getRepository("QwerLottoFrontendBundle:RateFormula")->findAll();
        
        return array(
            'formules'   => $rateFormules,
            'countries' => ""
            );
    }
    
     /**
       * @Route("/changeSelectData/{count}", name="reload_select" ,defaults={"count" = 0})
       * @Template()
       */
    public function BetTypeAction($count)
    {
        $name = $this->container->get("frontend.nametype_service");
        $type = $name->getNameType($count);
        
        return array(
            'betType' => $type
        );
    }
 
}
