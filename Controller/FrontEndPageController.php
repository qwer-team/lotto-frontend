<?php

namespace Qwer\LottoFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Qwer\LottoFrontendBundle\Entity\Country;

class FrontEndPageController extends Controller
{
    /**
     * @Route("/hello/lotto/undle")
     * @Template()
     */
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getEntityManager();
        $countries = $em->getRepository('QwerLottoFrontendBundle:Country\Country')
                    ->findAllOrderedByName();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:index.html.twig', array(
            'countries' => $countries,
        ));
    }
}
