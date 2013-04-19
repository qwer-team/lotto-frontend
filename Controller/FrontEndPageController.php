<?php

namespace Qwer\LottoFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FrontEndPageController extends Controller
{
    /**
     * @Route("/hello/lotto/bundle")
     * @Template()
     */
    public function indexAction()
    {
        $tr = $this->get('translator');
        $message = $tr->trans('time.parameter');
        $em = $this->getDoctrine()->getManager();
        $lottery = $em->getRepository('QwerLottoBundle:Type')
                    ->findAllOrderedByName();
        $timeExpire = $em->getRepository('QwerLottoBundle:Draw')
                    ->getNearestDraws();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:index.html.twig', array(
            'lottery' => $lottery, 'timeExpire' => $timeExpire, 'message' => $message,
        ));
    }
    
    
    public function leftMenuAction()
    {
       $em = $this->getDoctrine()->getManager();
       $countries = $em->getRepository('QwerLottoFrontendBundle:Country')
                    ->findAllOrderedByName();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:leftMenu.html.twig', array(
            'countries' => $countries,
        ));
    }
    
    public function nextLottoDrawsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $nextDraws = $em->getRepository('QwerLottoBundle:Draw')
                    ->findNextLottoDraws(4);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:nextLottoDraws.html.twig', array(
            'nextDraws' => $nextDraws,
        ));
    }
    
    /**
     * @Route("/hello/lotto/bundle/schedule")
     * @Template()
     */
    public function fullScheduleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $countries = $em->getRepository('QwerLottoFrontendBundle:Country')
                    ->getFullSchedule();     
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:fullSchedule.html.twig', array(
            'countries' => $countries,
        ));
    }
    
    
    public function  lastResultsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lastResults = $em->getRepository('QwerLottoBundle:Result')
                    ->getLastResults(4);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:lastResults.html.twig', array(
            'lastResults' => $lastResults,
        ));
    }
}
