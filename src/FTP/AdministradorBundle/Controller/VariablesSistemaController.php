<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FTP\AdministradorBundle\Entity\VariablesSistema;
use FTP\AdministradorBundle\Form\VariablesSistemaType;

/**
 * VariablesSistema controller.
 *
 * @Route("/variablessistema")
 */
class VariablesSistemaController extends Controller
{

    /**
     * Lists all VariablesSistema entities.
     *
     * @Route("/", name="variablessistema")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FTPAdministradorBundle:VariablesSistema')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new VariablesSistema entity.
     *
     * @Route("/", name="variablessistema_create")
     * @Method("POST")
     * @Template("FTPAdministradorBundle:VariablesSistema:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new VariablesSistema();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('variablessistema_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a VariablesSistema entity.
     *
     * @param VariablesSistema $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(VariablesSistema $entity)
    {
        $form = $this->createForm(new VariablesSistemaType(), $entity, array(
            'action' => $this->generateUrl('variablessistema_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new VariablesSistema entity.
     *
     * @Route("/new", name="variablessistema_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new VariablesSistema();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a VariablesSistema entity.
     *
     * @Route("/{id}", name="variablessistema_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FTPAdministradorBundle:VariablesSistema')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VariablesSistema entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing VariablesSistema entity.
     *
     * @Route("/{id}/edit", name="variablessistema_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FTPAdministradorBundle:VariablesSistema')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VariablesSistema entity.');
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
    * Creates a form to edit a VariablesSistema entity.
    *
    * @param VariablesSistema $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(VariablesSistema $entity)
    {
        $form = $this->createForm(new VariablesSistemaType(), $entity, array(
            'action' => $this->generateUrl('variablessistema_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing VariablesSistema entity.
     *
     * @Route("/{id}", name="variablessistema_update")
     * @Method("PUT")
     * @Template("FTPAdministradorBundle:VariablesSistema:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FTPAdministradorBundle:VariablesSistema')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find VariablesSistema entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('variablessistema_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a VariablesSistema entity.
     *
     * @Route("/{id}", name="variablessistema_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FTPAdministradorBundle:VariablesSistema')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find VariablesSistema entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('variablessistema'));
    }

    /**
     * Creates a form to delete a VariablesSistema entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('variablessistema_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
