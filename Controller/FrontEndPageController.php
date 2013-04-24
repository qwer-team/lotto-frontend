<?php

namespace Qwer\LottoFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\View\TwitterBootstrapView;
use Symfony\Component\HttpFoundation\Request;

class FrontEndPageController extends Controller
{

    /**
     * @Route("/hello/lotto/bundle")
     * @Template()
     */
    public function indexAction(Request $request)
    {

        $token = $request->get("token");
        if($token != "") {
            $session = $request->getSession();
            $session->set("token", $token);
        }
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

    public function lastResultsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lastResults = $em->getRepository('QwerLottoBundle:Result')
                ->getLastResults(4);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:lastResults.html.twig', array(
                    'lastResults' => $lastResults,
        ));
    }

    /**
     * @Route("/hello/lotto/bundle/fullres/{page}",
     *         name="fullres",
     *         requirements={"page" = "\d+"},
     *         defaults={"page" = "1"}
     * )
     * @Template()
     * @param int $page
     */
    public function fullResultsAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $adapter = $em->getRepository('QwerLottoBundle:Result')
                ->getFullResults();
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(1);
        $pagerfanta->setCurrentPage($page);
        $currentPageResults = $pagerfanta->getCurrentPageResults();


        $routeGenerator = array($this, 'routeGenerator');

        $view = new TwitterBootstrapView();
        $html = $view->render($pagerfanta, $routeGenerator, array(
            "prev_message" => "&laquo;",
            "next_message" => "&raquo;",
            "css_active_class" => " ",
            "css_disabled_class" => " "
                ));
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:fullResults.html.twig', array(
                    'fullResults' => $currentPageResults,
                    'paginator' => $html
        ));
    }

    public function routeGenerator($page)
    {
        return $this->generateUrl("fullres", array("page" => $page));
    }

    /**
     * @Route("/changeSelectData/{count}",
     *         name="reload_select" ,
     *         defaults={"count" = 0})
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

    public function FormulaBlockAction()
    {
        $em = $this->container->get('doctrine');
        $rateFormules = $em->getRepository("QwerLottoFrontendBundle:RateFormula")->findAll();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:FormulaBlock.html.twig', array(
                    'formules' => $rateFormules,
        ));
    }

    /**
     * @Route("/lotto/{id}",
     *         name="lotto_page" ,
     *         defaults={"id" = 1})
     * @Template()
     */
    public function LottoPageAction()
    {
        $em = $this->getDoctrine()->getManager();

       // $currentLotto = $em->getRepository('QwerLottoBundle:Type')->find($id);
        //$rate = $em->getRepository('QwerLottoBundle:Type')->findOneBy($client);

        return $this->render('QwerLottoFrontendBundle:FrontEndPage:LottoPage.html.twig', array(
                    'currentLotto' => $currentLotto="",
                    'rate' => $rate="",
        ));
    }

}
