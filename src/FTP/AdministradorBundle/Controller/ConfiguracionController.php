<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FTP\AdministradorBundle\Entity\Configuracion;


/**
 * Configuracion controller.
 *
 */
class ConfiguracionController extends Controller
{

    /**
    * Redirecciona a la vista inicial de Configuraciones.
    * @param object $request Objeto peticion de Symfony 2.7.
    * @return vista principal módulo Configuraciones.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.7
    * @category FTP\Configuraciones
    */

    public function indexAction(Request $request){
        
        $session=$request->getSession();
        $em=$this->getDoctrine()->getManager();
        $entity=$em->createQueryBuilder()->select('c')
        ->from('FTPAdministradorBundle:Configuracion','c')->getQuery()->getResult();
        $control=($entity)?1:0;
       // dump($entity);exit;
        return $this->render('FTPAdministradorBundle:Configuracion:index.html.twig', array(
            'permisos'=>$session->get('permisos'),
            'modulo'=>'configuracion',
            'nombre'=>$session->get('nombre'),
            'entity'=>$entity,
            'control'=>$control
        ));
    }

    public function updateAction(Request $request){
      
      $json=array();
      $response = new Response();
      $response->headers->set('Content-Type', 'application/json');
      
      $em=$this->getDoctrine()->getManager();

      $clave=$request->get('clave');
      $usuario=$request->get('usuarios');
      $host=$request->get('host');
      $id=$request->get('id');

      if($request->get('control')==1){
        //dump($_POST);exit;
        
        $em->createQueryBuilder()->update('FTPAdministradorBundle:Configuracion','c')
        ->set('c.clave','?1')
        ->set('c.usuario','?2')
        ->set('c.host','?3')
        ->where('c.id=?4')
        ->setParameter(1,$clave)
        ->setParameter(2,$usuario)
        ->setParameter(3,$host)
        ->setParameter(4,$id)
        ->getQuery()->execute();

        $json['respuesta']=1;
        $json['control']=1;
      }
      if($request->get('control')==0){
        $conexion=$em->getConnection();
        $aux=$conexion->prepare('INSERT INTO  configuracion VALUES(NULL,?,?,?)');
        $aux->bindValue(1,$clave);
        $aux->bindValue(2,$usuario);
        $aux->bindValue(3,$host);
        $aux->execute();
        $json['respuesta']=2;
        $json['control']=1;
        $json['id']=$conexion->lastInsertId();
      }

     
      $response->setContent(json_encode($json));
      return $response;
        
    }
  
}
