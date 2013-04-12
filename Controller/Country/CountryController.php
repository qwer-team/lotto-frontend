<?php

namespace Qwer\LottoFrontendBundle\Controller\Country;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Qwer\LottoFrontendBundle\Entity\Country\Country;
use Qwer\LottoFrontendBundle\Form\Country\CountryType;

/**
 * Country\Country controller.
 *
 */
class CountryController extends Controller
{
    /**
     * Lists all Country\Country entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('QwerLottoFrontendBundle:Country\Country')->findAll();

        return $this->render('QwerLottoFrontendBundle:Country/Country:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Country\Country entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Country();
        $form = $this->createForm(new CountryType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('country_show', array('id' => $entity->getId())));
        }

        return $this->render('QwerLottoFrontendBundle:Country/Country:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Country\Country entity.
     *
     */
    public function newAction()
    {
        $entity = new Country();
        $form   = $this->createForm(new CountryType(), $entity);

        return $this->render('QwerLottoFrontendBundle:Country/Country:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Country\Country entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoFrontendBundle:Country\Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Country\Country entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('QwerLottoFrontendBundle:Country/Country:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Country\Country entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoFrontendBundle:Country\Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Country\Country entity.');
        }

        $editForm = $this->createForm(new CountryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('QwerLottoFrontendBundle:Country/Country:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Country\Country entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoFrontendBundle:Country\Country')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Country\Country entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new CountryType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('country_edit', array('id' => $id)));
        }

        return $this->render('QwerLottoFrontendBundle:Country/Country:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Country\Country entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QwerLottoFrontendBundle:Country\Country')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Country\Country entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('country'));
    }

    /**
     * Creates a form to delete a Country\Country entity by id.
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
