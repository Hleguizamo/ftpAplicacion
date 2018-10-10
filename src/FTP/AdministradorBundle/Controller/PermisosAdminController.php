<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FTP\AdministradorBundle\Entity\PermisosAdmin;
use FTP\AdministradorBundle\Form\PermisosAdminType;

/**
 * PermisosAdmin controller.
 *
 * @Route("/permisosadmin")
 */
class PermisosAdminController extends Controller
{

    /**
     * Lists all PermisosAdmin entities.
     *
     * @Route("/", name="permisosadmin")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FTPAdministradorBundle:PermisosAdmin')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new PermisosAdmin entity.
     *
     * @Route("/", name="permisosadmin_create")
     * @Method("POST")
     * @Template("FTPAdministradorBundle:PermisosAdmin:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new PermisosAdmin();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('permisosadmin_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a PermisosAdmin entity.
     *
     * @param PermisosAdmin $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PermisosAdmin $entity)
    {
        $form = $this->createForm(new PermisosAdminType(), $entity, array(
            'action' => $this->generateUrl('permisosadmin_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PermisosAdmin entity.
     *
     * @Route("/new", name="permisosadmin_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new PermisosAdmin();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a PermisosAdmin entity.
     *
     * @Route("/{id}", name="permisosadmin_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FTPAdministradorBundle:PermisosAdmin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PermisosAdmin entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing PermisosAdmin entity.
     *
     * @Route("/{id}/edit", name="permisosadmin_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FTPAdministradorBundle:PermisosAdmin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PermisosAdmin entity.');
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
    * Creates a form to edit a PermisosAdmin entity.
    *
    * @param PermisosAdmin $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(PermisosAdmin $entity)
    {
        $form = $this->createForm(new PermisosAdminType(), $entity, array(
            'action' => $this->generateUrl('permisosadmin_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing PermisosAdmin entity.
     *
     * @Route("/{id}", name="permisosadmin_update")
     * @Method("PUT")
     * @Template("FTPAdministradorBundle:PermisosAdmin:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FTPAdministradorBundle:PermisosAdmin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PermisosAdmin entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('permisosadmin_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a PermisosAdmin entity.
     *
     * @Route("/{id}", name="permisosadmin_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FTPAdministradorBundle:PermisosAdmin')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PermisosAdmin entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('permisosadmin'));
    }

    /**
     * Creates a form to delete a PermisosAdmin entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('permisosadmin_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
