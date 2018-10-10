<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FTP\AdministradorBundle\Entity\Transferencista;
use FTP\AdministradorBundle\Form\TransferencistaType;

/**
 * Transferencista controller.
 *
 * @Route("/transferencista")
 */
class TransferencistaController extends Controller
{

    /**
     * Lists all Transferencista entities.
     *
     * @Route("/", name="transferencista")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FTPAdministradorBundle:Transferencista')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Transferencista entity.
     *
     * @Route("/", name="transferencista_create")
     * @Method("POST")
     * @Template("FTPAdministradorBundle:Transferencista:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Transferencista();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('transferencista_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Transferencista entity.
     *
     * @param Transferencista $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Transferencista $entity)
    {
        $form = $this->createForm(new TransferencistaType(), $entity, array(
            'action' => $this->generateUrl('transferencista_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Transferencista entity.
     *
     * @Route("/new", name="transferencista_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Transferencista();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Transferencista entity.
     *
     * @Route("/{id}", name="transferencista_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FTPAdministradorBundle:Transferencista')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transferencista entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Transferencista entity.
     *
     * @Route("/{id}/edit", name="transferencista_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FTPAdministradorBundle:Transferencista')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transferencista entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Transferencista entity.
    *
    * @param Transferencista $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Transferencista $entity)
    {
        $form = $this->createForm(new TransferencistaType(), $entity, array(
            'action' => $this->generateUrl('transferencista_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Transferencista entity.
     *
     * @Route("/{id}", name="transferencista_update")
     * @Method("PUT")
     * @Template("FTPAdministradorBundle:Transferencista:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FTPAdministradorBundle:Transferencista')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Transferencista entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('transferencista_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Transferencista entity.
     *
     * @Route("/{id}", name="transferencista_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FTPAdministradorBundle:Transferencista')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Transferencista entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('transferencista'));
    }

    /**
     * Creates a form to delete a Transferencista entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('transferencista_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
