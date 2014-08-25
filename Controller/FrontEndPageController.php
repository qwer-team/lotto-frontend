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
use Qwer\UserBundle\Entity\UserEx;
use JMS\DiExtraBundle\Annotation as DI;


class FrontEndPageController extends Controller
{
    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;


    public function indexAction(Request $request, $id = 16)
    {
        $tokenStr = $request->get("token");
       // $tokenStr = "77d1774mt2tov9fu2rhmto9f42";//$request->get("token");
     //  print("request->get(`token`) = ". $tokenStr."<br/>");
        $clientId = 1;
        if($tokenStr!="")  {
 
        $url="http://195.137.167.10/ferapont/lotto/ClientAuth";
        
         $ch = curl_init($url);
            
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'token='.$tokenStr );
            $responseRaw = curl_exec($ch);
     //       echo  $responseRaw ;
            
            curl_close($ch);
            $resp  = json_decode($responseRaw);
         if(property_exists($resp, 'user_id')  ) {
            
         
     //      print_r($resp) ;
    
     
        $this->em = $this->container->get("doctrine.orm.entity_manager");
        $token = $this->findToken($resp->user_id);
        if (!$token) {
          
        $class = $this->get('service_container')->getParameter('users.token_class');
        $token = new $class();
        
        $namespace = "QwerLottoBundle:User";
        $uRepo = $this->em->getRepository($namespace);
         $user = $uRepo->findOneById(1);
       
         $token->setUser($user);
         $token->setExternalId($resp->user_id);
       
        
        $currencyRepo = $this->getCurrencyRepo();
        $currency = $currencyRepo->findOneByCode($resp->currency);
        $token->setCurrency($currency);
          
         }  
          $token->updateExpireDate();  
          $token->setToken($tokenStr);
      //     print($token->getToken()." -- ");
        $this->em->persist($token);
          $this->em->flush();     
            
        $session = $request->getSession();
        
        $session->set("token", $tokenStr);
        // print("session(`token`) = ". $session->get("token")."<br/>");
        }
    }
        $tr = $this->get('translator');
        $message = $tr->trans('time.parameter');
        $em = $this->getDoctrine()->getManager();
        $lottery = $em->getRepository('QwerLottoBundle:Type')->findAllOrderedByName();
        $lotterySlider = $em->getRepository('QwerLottoBundle:Type')->findAllForSlider();
        
        $timeExpire = $em->getRepository('QwerLottoBundle:Draw')
                ->getNearestDraws();
        $url = $request->getPathInfo();
        $locale = $this->getRequest()->getLocale();
        //print($locale);
        $currentLotto = $em->getRepository('QwerLottoBundle:Type')->find($id);
        return $this->render('QwerLottoFrontendBundle:FrontEndPage:index.html.twig', array(
                    'curLotto' => $currentLotto,
                    'lottery' => $lottery,
                    'lotterySlider' => $lotterySlider,
                    'timeExpire' => $timeExpire,
                    'message' => $message,
                    'id' => $id,
                    'index' => ($url == "/"),
                    'locale' => $locale,
                    'menu' => '',
                ));
    }
    
    
    private function findToken($externalId)
    {
        $class = $this->get('service_container')->getParameter('users.token_class');
        $repo = $this->em->getRepository($class);

        $criteria = array(
            
            "externalId" => $externalId
        );

        $token = $repo->findOneBy($criteria);

        return $token;
    }

   

    private function getCurrencyRepo()
    {
        $namespace = "QwerLottoDocumentsBundle:Currency";
        $curRepo = $this->em->getRepository($namespace);

        return $curRepo;
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

    public function fullResultsAction(Request $request,   $page, $_locale)
    {
       // print($lottoType);
        $filter = $this->getResultFilter();
        $em = $this->getDoctrine()->getManager();
        // if($filter->getLottoType()==0 ){
        $adapter = $em->getRepository('QwerLottoBundle:Result')->getFullResults($filter);
        $types = $em->getRepository('QwerLottoBundle:Type')->findAll();
        /* } else {
            $adapter = $em->getRepository('QwerLottoBundle:Result')->getFullResults($filter);
            
        }
        */
         
       $types= $em->getRepository('QwerLottoBundle:Type')->findAll();
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(20);
        $pagerfanta->setCurrentPage($page);
        $currentPageResults = $pagerfanta->getCurrentPageResults();


        $routeGenerator = array($this, 'routeGenerator');

        $view = new TwitterBootstrapView();
        $html = "";
        if($pagerfanta->count() > 20)
        {
            $html = $view->render($pagerfanta, $routeGenerator, array(
                "prev_message" => "&laquo;",
                "next_message" => "&raquo;",
                "css_active_class" => " ",
                "css_disabled_class" => " "
                    ));
        }
        $options =array('attr' => array('types' => $types));
 
        $filterForm = $this->createForm(new ResultFilterType(), $filter, $options  );
      
        
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
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository('QwerLottoBundle:Type')->findAll();
        $options =array('attr' => array('types' => $types));
        
        $filterForm = $this->createForm(new ResultFilterType(), $filter, $options );
        $filterForm->bindRequest($request);
        if($filterForm->isValid()){
            $request->getSession()->set('result_filter', $filter);
        }
        // if(empty($filter->getLottoType())) {
            return $this->redirect($this->generateUrl('fullres'));
        /* } else {
            return $this->redirect($this->generateUrl('fullresType', array("lottoType" => 4)));
            
        } */
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
       return $this->render('QwerLottoFrontendBundle:FrontEndPage:LottoPlace.html.twig', array(
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
