<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FTP\AdministradorBundle\Entity\Administrador;


/**
 * Administrador controller.
 *
 */
class AdministradorController extends Controller
{

    /**
    * Redirecciona a la vista inicial de administradores.
    * @param object $request Objeto peticion de Symfony 2.7.
    * @return vista principal módulo administradores.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.7
    * @category FTP\Administrador
    */

    public function indexAction(Request $request){

        $session=$request->getSession();
        return $this->render('FTPAdministradorBundle:Administrador:index.html.twig', array(
            'permisos'=>$session->get('permisos'),
            'modulo'=>'administrador',
            'nombre'=>$session->get('nombre')
        ));
    }

    /**
    * Devuelve una respuesta en xml con todos los administradores registrados.
    * @param object $request Objeto peticion de Symfony 2.6.
    * @return Objeto xml con administradores.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    * @category FTP\Administrador
    */
    public function xmlAction(Request $request){

       if ($request->isXmlHttpRequest()){

          $busqueda = $this->get('busquedaAdministrador');
          $where=$busqueda->busqueda();
          $OrdenTipo = $request->get('sord');
          $OrdenCampo = $request->get('sidx');
          $rows = $request->get('rows');
          $pagina = $request->get('page');
          $paginacion = ($pagina * $rows) - $rows;

          $em = $this->getDoctrine()->getManager();
          $entities = $em->createQuery(' SELECT a FROM FTPAdministradorBundle:Administrador a  '.$where.' ORDER BY '.$OrdenCampo.' '.$OrdenTipo);
          $entities->setMaxResults($rows);
          $entities->setFirstResult($paginacion);
          $entities = $entities->getResult();   
          
          $Contador=$em->createQuery("SELECT COUNT(a.id) AS contador FROM FTPAdministradorBundle:Administrador a ".$where)->getSingleResult();
          $numRegistros = $Contador['contador'];
          $totalPagina = ceil($numRegistros / $rows);            
          $response = new Response();
          $response->setStatusCode(200);

          $response->headers->set('Content-Type', 'text/xml');
          return $this->render('FTPAdministradorBundle:Administrador:index.xml.twig', array(
                  'entities' => $entities,'numRegistros' => $numRegistros,
                  'maxPagina' => $totalPagina,'pagina' => $pagina), $response);
      }

    } 

     /**
    * Redirecciona a la vista del formulario para crear un administrador.
    * @param object $request Objeto peticion de Symfony 2.6.
    * @return 
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    * @category FTP\Administrador
    */

    public function newAction(){

        return $this->render('FTPAdministradorBundle:Administrador:new.html.twig');
    }

    /**
    * Crea un nuevo administrador
    * @param object $request Objeto peticion de Symfony 2.7.
    * @return json con el estado final de la tarea.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.7
    * @category FTP\Administrador
    */
    public function createAction(Request $request){
        $session=$request->getSession();
        $em=$this->getDoctrine()->getManager();
        $conexion=$em->getConnection();

        $json=array();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try{
            //campos vacios.
            if($request->get('nombres')==''||
               $request->get('usuarios')==''||
               $request->get('clave')==''){
               $json['respuesta']=99;
               $response->setContent(json_encode($json));
               return $response;
            } 
           
            //logitid de la contraseña.
            if(strlen($request->get('clave'))<4){
              $json['respuesta']=4;
              $response->setContent(json_encode($json));
              return $response;
            }

            //comparacion de claves.
            if(strcmp($request->get('confirmacion'),$request->get('clave')) !=0){
               $json['respuesta']=1;
               $response->setContent(json_encode($json));
               return $response;
            }
            //disponibilidad del nombre de usuario.
            $data=$em->createQueryBuilder()
            ->select('COUNT(a.id) contador')
            ->from('FTPAdministradorBundle:Administrador','a')
            ->where('a.usuario=?1')
            ->setParameter(1,$request->get('usuarios'))
            ->getQuery()->getOneOrNullResult();

            if($data['contador']>=1){ 
              $json['respuesta']=3;
              $response->setContent(json_encode($json));
              return $response;
            }

            $util=$this->get('utilidadesAdministrador');
            $util->registralog('1.1 Crear administrador ['.$request->get('nombre').'. Usuario:'.$request->get('usuario').' ]  ',$session->get('administradorId'));
            //Crear administrador.
            $administrador=new Administrador();
            $administrador->setNombre($request->get('nombres'));
            $administrador->setUsuario($request->get('usuarios'));
            $administrador->setClave($request->get('clave'));
            $administrador->setEmail($request->get('email'));
            $administrador->setSeguimiento($request->get('seguimiento'));
            $administrador->setFechaCreado(new \DateTime('now'));
            $administrador->setEstado($request->get('estado'));
            $em->persist($administrador);
            $em->flush();

            $ultimoId=$administrador->getId();
            //crear permisos.
            $util=$this->container->get('utilidadesAdministrador');
            $util->registralog('1.1 Creacion de administrador[ '.$request->get('nombres').' ] ',$session->get('administradorId'));

            $arrayModulos=$conexion->query('SELECT id_permisos FROM menu_administradores');
            $arrayModulos=$arrayModulos->fetchAll();
           //dump($arrayModulos);exit;
            $sql='INSERT INTO permisos_administradores VALUES ';
            $values='';
            foreach ($arrayModulos as $k => $v) {
              if($values!='')
                  $values.=',';
                $values.='(NULL,'.$ultimoId.','.$v['id_permisos'].',0)';
            }
            $conexion->query($sql.$values);


            $response->setStatusCode(200);
        }catch(Exception $e){
            $response->setStatusCode(500); 
        }

          $json['respuesta']=2;
           $response->setContent(json_encode($json));
        return $response;
    }

    /**
    * Genera la vista junto co la entidad administrdor a editar.
    * @param object $request Objeto peticion de Symfony 2.6.
    * @return vista del formulario de edición con los datos a editar.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    * @category Facturación\Administrador
    * @Template ("FTPAdministradorBundle:Administrador:edit.html.twig")
    */
    public function editAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $conexion=$em->getConnection();
        $entity = $em->getRepository('FTPAdministradorBundle:Administrador')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Administrador entity.');
        }

        // permisos actuales.

          //traer todos los modulos del menú.
          // colocar el id como llave del array.
          $arrayModulos=$em->createQueryBuilder()
          ->select('ma.idPermisos id,ma.descripcionPermiso nombre')
          ->from('FTPAdministradorBundle:MenuAdministradores','ma')
          ->getQuery()
          ->getArrayResult();
          $modulos=array();
          foreach ($arrayModulos as $k => $v) {
            $modulos[$v['id']]=$v['nombre'];
          }
          unset($arrayModulos);
          //dump($modulos);exit;

          //traer todos los permisos el administrador seleccionado.
          //colocael el id como llave del array.
          $arrayPermisos=$em->createQueryBuilder()
          ->select('pa.idPermiso modulo,pa.tipoPermiso permiso')
          ->from('FTPAdministradorBundle:PermisosAdministradores','pa')
          ->where('pa.idAdministrador=?1')
          ->setParameter(1,$id)
          ->getQuery()
          ->getArrayResult();
          //dump($arrayPermisos);exit;
          $permisos=array();
          foreach ($arrayPermisos as $k => $v) {
            $permisos[$v['modulo']]=$v['permiso'];
          }
          unset($arrayPermisos);
        return array(
            'entity'=> $entity,
            'permisos'=>$permisos,
            'modulos'=>$modulos
        );
    }

     /**
    * Actualiza los datos del administrador según los parámetros enviados.
    * @param id del administrador.Object $request Objeto peticion de Symfony 2.6.
    * @return json con el estado final de la tarea.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    * @category FTP\Administrador
    */
    
    public function updateAction(Request $request,$id){
        $session=$request->getSession();
        $em=$this->getDoctrine()->getManager(); 

        $json=array();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try{
            //campos vacios.
            if($request->get('nombres')==''||
               $request->get('usuarios')==''||
               $request->get('clave')==''){
               $json['respuesta']=99;
               $response->setContent(json_encode($json));
               return $response;
            } 
           
            //logitid de la contraseña.
            if(strlen($request->get('clave'))<4){
              $json['respuesta']=4;
              $response->setContent(json_encode($json));
              return $response;
            }

            //comparacion de claves.
            if(strcmp($request->get('confirmacion'),$request->get('clave')) !=0){
               $json['respuesta']=1;
               $response->setContent(json_encode($json));
               return $response;
            }

            if(strcmp($request->get('usuarioActual'),$request->get('usuarios')) !=0){

                $data=$em->createQueryBuilder()
                ->select('COUNT(a.id) contador')
                ->from('FTPAdministradorBundle:Administrador','a')
                ->where('a.usuario=?1')
                ->setParameter(1,$request->get('usuarios'))
                ->getQuery()
                ->getOneOrNullResult();
                //usuarioActual
                if($data['contador']>=1){ 
                  $json['respuesta']=3;
                  $response->setContent(json_encode($json));
                  return $response;
                }
            }
            //actualizar datos del administrador
            $em->createQueryBuilder()
            ->update('FTPAdministradorBundle:Administrador','a')
            ->set('a.nombre','?1')
            ->set('a.usuario','?2')
            ->set('a.clave','?3')
            ->set('a.email','?4')
            ->set('a.seguimiento','?5')
            ->set('a.estado','?6')
            ->set('a.modificadoId','?7')
            ->where('a.id=?8')
            ->setParameter(1,$request->get('nombres'))
            ->setParameter(2,$request->get('usuarios'))
            ->setParameter(3,$request->get('clave'))
            ->setParameter(4,$request->get('email'))
            ->setParameter(5,$request->get('seguimiento'))
            ->setParameter(6,$request->get('estado'))
            ->setParameter(7,$session->get('administradorId'))
            ->setParameter(8,$id)
            ->getQuery()
            ->execute();

            $response->setStatusCode(200);
            $util=$this->container->get('utilidadesAdministrador');
            $util->registralog('1.2 Actualizacion de administrador[ '.$request->get('nombres').' ] ',$session->get('administradorId'));
        }catch(Exception $e){
            $response->setStatusCode(500); 
        }

          $json['respuesta']=2;
           $response->setContent(json_encode($json));
        return $response;
    }

    /**
    * Elimina el administrador seleccionado.
    * @param id del administrador.Object $request Objeto peticion de Symfony 2.6.
    * @return json y/o código de estatus con el estado final de la tarea.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    * @category FTP\Administrador
    */

    public function deleteAction(Request $request){
        $session=$request->getSession();
        $em=$this->getDoctrine()->getManager(); 
       // dump($_POST);exit;
        $json=array();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try{

            //validar historial del administrador.
            $data=$em->createQueryBuilder()
            ->select('COUNT(la.id) contador')
            ->from('FTPAdministradorBundle:LogAdministrador','la')
            ->where('la.idAdministrador=?1')
            ->setParameter(1,$request->get('id'))
            ->getQuery()
            ->getOneOrNullResult();
            //usuarioActual
            if($data['contador']>=1){ 
              $json['respuesta']=3;
              $response->setContent(json_encode($json));
              return $response;
            }
          
            $em->createQueryBuilder()
            ->delete('FTPAdministradorBundle:Administrador','a')
            ->where('a.id=?1')
            ->setParameter(1,$request->get('id'))
            ->getQuery()
            ->execute();

            $em->createQueryBuilder()
            ->delete('FTPAdministradorBundle:PermisosAdministradores','pa')
            ->where('pa.idAdministrador=?1')
            ->setParameter(1,$request->get('id'))
            ->getQuery()
            ->execute();

            $util=$this->container->get('utilidadesAdministrador');
            $util->registralog('1.3 Se elimina el administrador[ '.$request->get('nombre').' ] ',$session->get('administradorId'));

            $response->setStatusCode(200);
            $json['respuesta']=1;
        }catch(Exception $e){
            $response->setStatusCode(500); 
        }
        $response->setContent(json_encode($json));
        return $response;  
    }

    /**
    * Acción que actualiza los permisos de acceso a los módulos para el administrador seleccionado.
    * @param id del administrador.Object $request Objeto peticion de Symfony 2.6.
    * @return json y/o código de estatus con el estado final de la tarea.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    * @category FTP\Administrador
    */

    public function updatePermisosAction(Request $request,$id){
      $session=$request->getSession();
      $json=array();
      $response = new Response();
      $response->headers->set('Content-Type', 'application/json');
      $conexion=$this->getDoctrine()->getManager()->getConnection();
      try{
         //actualizar permisos de los módulos.  
          //descartar permisos actuales. 
          $sql=' DELETE FROM permisos_administradores WHERE id_administrador=? ';
            $aux=$conexion->prepare($sql);
            $aux->bindValue(1,$id);
            $aux->execute();  
          //insertar los valores recibidos por $_POST.
            $sql='INSERT INTO permisos_administradores VALUES ';
            $values='';
            foreach ($request->get('permisos') as $k => $v) {
              if($values!='')
                  $values.=',';
                $values.='(NULL,'.$id.','.$k.','.$v.')';
            }
            $conexion->query($sql.$values);
            $util=$this->container->get('utilidadesAdministrador');
            $util->registralog('1.3 Se actualizan los permisos para el administrador[ '.$request->get('nombreAdmin').' ] ',$session->get('administradorId'));
          $response->setStatusCode(200);
          $json['respuesta']=1;
        }catch(Exception $e){
            $response->setStatusCode(500); 
        }
        $response->setContent(json_encode($json));
        return $response; 

    }

}
