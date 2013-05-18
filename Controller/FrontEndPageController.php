<?php

namespace Qwer\LottoFrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\View\TwitterBootstrapView;
use Symfony\Component\HttpFoundation\Request;
use Qwer\LottoDocumentsBundle\Form\BodyType;
use Qwer\LottoDocumentsBundle\Entity\Request\Body;

class FrontEndPageController extends Controller
{

    public function indexAction(Request $request, $id = 1)
    {

        $token = $request->get("token");
        $session = $request->getSession();
        $session->set("token", $token);
        $tr = $this->get('translator');
        $message = $tr->trans('time.parameter');
        $em = $this->getDoctrine()->getManager();
        $lottery = $em->getRepository('QwerLottoBundle:Type')
                ->findAllOrderedByName();
        $timeExpire = $em->getRepository('QwerLottoBundle:Draw')
                ->getNearestDraws();
        $url = $request->getPathInfo();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:index.html.twig', array(
                    'lottery' => $lottery,
                    'timeExpire' => $timeExpire,
                    'message' => $message,
                    'id' => $id,
                    'index' => ($url == "/"),
                ));
    }

    public function leftMenuAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $countries = $em->getRepository('QwerLottoFrontendBundle:Country')
                ->findAllOrderedByName();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:leftMenu.html.twig', array(
                    'countries' => $countries,
                    'locale' => $request->getLocale(),
                ));
    }

    public function nextLottoDrawsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $nextDraws = $em->getRepository('QwerLottoBundle:Draw')
                ->findNextLottoDraws(4);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:nextLottoDraws.html.twig', array(
                    'nextDraws' => $nextDraws,
                    'locale' => $request->getLocale(),
                ));
    }

    public function fullScheduleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $countries = $em->getRepository('QwerLottoFrontendBundle:Country')
                ->getFullSchedule();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:fullSchedule.html.twig', array(
                    'countries' => $countries,
                    'locale' => $request->getLocale(),
                ));
    }

    public function lastResultsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $lastResults = $em->getRepository('QwerLottoBundle:Result')
                ->getLastResults(4);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:lastResults.html.twig', array(
                    'lastResults' => $lastResults,
                    'locale' => $request->getLocale(),
                ));
    }

    public function fullResultsAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $adapter = $em->getRepository('QwerLottoBundle:Result')
                ->getFullResults();
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($page);
        $currentPageResults = $pagerfanta->getCurrentPageResults();


        $routeGenerator = array($this, 'routeGenerator');

        $view = new TwitterBootstrapView();
        $html = "";
        if($pagerfanta->count() > 10)
        {
            $html = $view->render($pagerfanta, $routeGenerator, array(
                "prev_message" => "&laquo;",
                "next_message" => "&raquo;",
                "css_active_class" => " ",
                "css_disabled_class" => " "
                    ));
        }
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:fullResults.html.twig', array(
                    'fullResults' => $currentPageResults,
                    'paginator' => $html,
                    'locale' => $request->getLocale(),
                ));
    }

    public function routeGenerator($page)
    {
        return $this->generateUrl("fullres", array("page" => $page));
    }

    public function BetTypeAction($count)
    {
        $name = $this->container->get("frontend.nametype_service");
        $type = $name->getNameType($count);

        return $this->render('QwerLottoFrontendBundle:FrontEndPage:BetType.html.twig', array(
                    'betType' => $type
                ));
    }

    public function FormulaBlockAction()
    {
        $em = $this->container->get('doctrine');
        $rateFormules = $em->getRepository("QwerLottoFrontendBundle:RateFormula")->findAll();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:FormulaBlock.html.twig', array(
                    'formules' => $rateFormules,
                ));
    }

    public function LottoPageAction(Request $request, $index, $id = 1)
    {
        $body = new Body();
        $form = $this->createForm(new BodyType, $body);
        $em = $this->getDoctrine()->getManager();

        $currentLotto = $em->getRepository('QwerLottoBundle:Type')->find($id);
        //$rate = $em->getRepository('QwerLottoBundle:Type')->findOneBy($client);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:LottoPage.html.twig', array(
                    'lotto' => $currentLotto,
                    'form' => $form->createView(),
                    'index' => $index,
                    'locale' => $request->getLocale(),
                ));
    }

}
