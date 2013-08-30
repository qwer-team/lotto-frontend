<?php

namespace Qwer\LottoFrontendBundle\Controller\Country;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Qwer\LottoFrontendBundle\Entity\Country;
use Qwer\LottoFrontendBundle\Form\Country\CountryType;

/**
 * Country controller.
 *
 */
class CountryController extends Controller
{
    /**
     *
     * @var \Doctrine\ORM\EntityRepository 
     */
    private $repo;
    /**
     * Lists all Country entities.
     *
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $this->repo = $em->getRepository('QwerLottoFrontendBundle:Country');
        
        $qb = $this->repo->createQueryBuilder("country");
        $paginator = $this->get("qwer.pagination");
        $url = $this->generateUrl("country");
        $entities = $paginator->getIterator($qb, $url, $page, 10);
        $html = $paginator->getHtml(); 

        return $this->render('QwerLottoFrontendBundle:Country\Country:index.html.twig', array(
            'entities' => $entities,
            'pagination' => $html,
        ));
    }

    /**
     * Creates a new Country entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Country();
        $form = $this->createForm($this->get('qwer_lotto.form.country.type'), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('country_show', array('id' => $entity->getId())));
        }

        return $this->render('QwerLottoFrontendBundle:Country\Country:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Country entity.
     *
     */
    public function newAction()
    {
        $entity = new Country();
        $form   = $this->createForm($this->get('qwer_lotto.form.country.type'), $entity);

        return $this->render('QwerLottoFrontendBundle:Country\Country:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Country entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoFrontendBundle:Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Country entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('QwerLottoFrontendBundle:Country\Country:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Country entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoFrontendBundle:Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Country entity.');
        }
        
        $editForm = $this->createForm($this->get('qwer_lotto.form.country.type'), $entity);
        $deleteForm = $this->createDeleteForm($id);
       

        return $this->render('QwerLottoFrontendBundle:Country\Country:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Country entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoFrontendBundle:Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Country entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm($this->get('qwer_lotto.form.country.type'), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('country_edit', array('id' => $id)));
        }

        return $this->render('QwerLottoFrontendBundle:Country\Country:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Country entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QwerLottoFrontendBundle:Country')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Country entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('country'));
    }

    /**
     * Creates a form to delete a Country entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
