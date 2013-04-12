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
        $countries = $this->container->get("client.country_service");
        $countries =$countries->getCountries();
        
        return array(
            'countries' => $countries
            );
    }
}
