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
use Qwer\LottoFrontendBundle\Entity\Filter\ResultFilter;
use Qwer\LottoFrontendBundle\Form\Filter\ResultFilterType;

class FrontEndPageController extends Controller
{

    public function indexAction(Request $request, $id = 3)
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
        $locale = $this->getRequest()->getLocale();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:index.html.twig', array(
                    'lottery' => $lottery,
                    'timeExpire' => $timeExpire,
                    'message' => $message,
                    'id' => $id,
                    'index' => ($url == "/"),
                    'locale' => $locale,
                    'menu' => '',
                ));
    }

    public function leftMenuAction(Request $request, $_locale, $menu)
    {
        $em = $this->getDoctrine()->getManager();
        $countries = $em->getRepository('QwerLottoFrontendBundle:Country')
                ->findAllOrderedByName();
        //$this->getRequest()->setLocale($locale);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:leftMenu.html.twig', array(
                    'countries' => $countries,
                    'locale' => $_locale,
                    'menu' => $menu,
                ));
    }

    public function nextLottoDrawsAction(Request $request, $_locale)
    {
        $em = $this->getDoctrine()->getManager();
        $nextDraws = $em->getRepository('QwerLottoBundle:Draw')
                ->findNextLottoDraws(4);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:nextLottoDraws.html.twig', array(
                    'nextDraws' => $nextDraws,
                    'locale' => $_locale,
                ));
    }

    public function fullScheduleAction(Request $request, $_locale)
    {
        $em = $this->getDoctrine()->getManager();
        $countries = $em->getRepository('QwerLottoFrontendBundle:Country')
                ->getFullSchedule();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:fullSchedule.html.twig', array(
                    'countries' => $countries,
                    'locale' => $_locale,
                    'menu' => 'fullSchedule',
                ));
    }

    public function lastResultsAction(Request $request, $_locale)
    {
        $em = $this->getDoctrine()->getManager();
        $lastResults = $em->getRepository('QwerLottoBundle:Result')
                ->getLastResults(4);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:lastResults.html.twig', array(
                    'lastResults' => $lastResults,
                    'locale' => $_locale,
                ));
    }

    public function fullResultsAction(Request $request, $page, $_locale)
    {
        $filter = $this->getResultFilter();
        $em = $this->getDoctrine()->getManager();
        $adapter = $em->getRepository('QwerLottoBundle:Result')
                ->getFullResults($filter);
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
        $filterForm = $this->createForm(new ResultFilterType(), $filter);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:fullResults.html.twig', array(
                    'fullResults' => $currentPageResults,
                    'paginator' => $html,
                    'locale' => $_locale,
                    'filterForm' => $filterForm->createView(),
                    'menu' => 'fullResults',
                ));
    }

    
    public function updateResultFilterAction(Request $request){
        $filter = $this->getResultFilter();
        $filterForm = $this->createForm(new ResultFilterType(), $filter);
        $filterForm->bindRequest($request);
        if($filterForm->isValid()){
            $request->getSession()->set('result_filter', $filter);
        }
        
        return $this->redirect($this->generateUrl('fullres'));
    }

    

    private function getResultFilter(){
        $session = $this->getRequest()->getSession();
        if(!$session->has("result_filter")){
            $filter = new ResultFilter();
            $session->set("result_filter", $filter);
        } else {
            $filter = $session->get("result_filter");
        }
        return $filter;
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

     public function betCouponRequestAction(Request $request){
        $em = $this->getDoctrine()->getManager(); 
        $this->repo = $em->getRepository('QwerLottoDocumentsBundle:Bet');
        $betIds = $request->get("ids");
        $bets = $this->repo->getBetsByIds($betIds);
        
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:BetCoupon.html.twig', array(
                     'entities' => $bets,
                 ));
    }
    
    
    public function FormulaBlockAction($_locale)
    {
        $em = $this->container->get('doctrine');
        $rateFormules = $em->getRepository("QwerLottoFrontendBundle:RateFormula")->findAll();
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:FormulaBlock.html.twig', array(
                    'formules' => $rateFormules,
                    'locale' => $_locale,
                ));
    }

    public function LottoPageAction(Request $request, $index, $id = 3, $_locale)
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
                    'locale' => $_locale,
                ));
    }
    
    public function howToPlayAction($_locale)
    {
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:howToPlay.html.twig', array(
            'locale' => $_locale,
            'menu' => 'howToPlay',
        ));
    }
    
     public function aboutLottoAction($_locale)
    {
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:aboutLotto.html.twig', array(
            'locale' => $_locale,
            'menu' => 'aboutLotto',
        ));
    }

}
