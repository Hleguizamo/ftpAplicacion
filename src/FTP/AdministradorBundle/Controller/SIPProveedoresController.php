<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class SIPProveedoresController extends Controller{


   /**
    * Redirecciona a la vista inicial de logadmin.
    * @param object $request Objeto peticion de Symfony 2.6.
    * @return vista principal módulo logadmin.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    * @category FTP\LogAdmin
    */

    public function indexAction(Request $request){

        $session=$request->getSession();
        
        return $this->render('FTPAdministradorBundle:SIPproveedores:index.html.twig', array(
            'permisos'=>$session->get('permisos'),
            'modulo'=>'log_sip_proveedores',
            'nombre'=>$session->get('nombre')
        )); 
    }

/**
* Devuelve una respuesta en xml con todos los logs registrados.
* @param object $request Objeto peticion de Symfony 2.6.
* @return Objeto xml con logs.
* @author Julián casas <j.casas@waplicaciones.com.co>
* @since 2.6
* @category FTP\LogAdmin
*/

  public function xmlAction(Request $request){

    if ($request->isXmlHttpRequest()){

      $busqueda = $this->get('busquedaAdministrador');
      $where=$busqueda->busqueda();

      $filtroBusqueda=' ';
      $OrdenTipo = $request->get('sord');
      $OrdenCampo = $request->get('sidx');

      $rows = $request->get('rows');
      $pagina = $request->get('page');
      $paginacion = ($pagina * $rows) - $rows;

      $em = $this->getDoctrine()->getManager();
      $entities = $em->createQuery(' SELECT lsp.id, lsp.fecha, lsp.proveedor FROM FTPAdministradorBundle:LogSipProveedores lsp '.$where.' ORDER BY '.$OrdenCampo.' '.$OrdenTipo);
      $entities->setMaxResults($rows);
      $entities->setFirstResult($paginacion);
      $entities = $entities->getResult(); 

      $Contador=$em->createQuery(' SELECT COUNT(lsp.id) AS contador FROM FTPAdministradorBundle:LogSipProveedores lsp '.$where)->getSingleResult();
      $numRegistros = $Contador['contador'];
      $totalPagina = ceil($numRegistros / $rows);            
      $response = new Response();
      $response->setStatusCode(200);
     // dump($entities);exit;
      $response->headers->set('Content-Type', 'text/xml');
      return $this->render('FTPAdministradorBundle:SIPproveedores:index.xml.twig', array(
              'entities' => $entities,'numRegistros' => $numRegistros,
              'maxPagina' => $totalPagina,'pagina' => $pagina), $response);
    }//fin if

  }  

/**
* Genera el csv con registros de log
* @param object $request Objeto peticion de Symfony 2.6.
* @return entities con los log
* @author Julián casas <j.casas@waplicaciones.com.co>
* @since 2.6
* @category FTP\LogAdmin
*/


  public function detalleAction(Request $request){
    
    $em = $this->getDoctrine()->getManager();

    $id = $request->get('id');

    $proveedor = $em->createQueryBuilder()
                    ->select('lsp')
                    ->from('FTPAdministradorBundle:LogSipProveedores','lsp')
                    ->where('lsp.id=:id')
                    ->setParameter(':id',$id)
                    ->getQuery()
                    ->getOneOrNullResult();
 
    $archivos = $em->createQueryBuilder()
                    ->select('la')
                    ->from('FTPAdministradorBundle:LogArchivos','la')
                    ->where('la.logAdmin=:id')
                    ->setParameter(':id',$id)
                    ->getQuery()
                    ->getResult();

    $estructuraArchivos = $productos = array();
    $pedidos =  $novedades= array();

    foreach ($archivos as $archivo) {
      
      //estructura archivos
      $data = $em->createQueryBuilder()
                  ->select('lea')
                  ->from('FTPAdministradorBundle:LogEstructuraArchivos','lea')
                  ->where('lea.logAdmin=:id')
                  ->setParameter(':id',$id)
                  ->andWhere('lea.logArchivo=:archivo')
                  ->setParameter(':archivo',$archivo->getId())
                  ->getQuery()
                  ->getResult();
      if($data)
        $estructuraArchivos[ $archivo->getId() ] = $data;

      $data = null;

      //productos
      $data = $em->createQueryBuilder()
                  ->select('lp')
                  ->from('FTPAdministradorBundle:LogProductos','lp')
                  ->where('lp.logAdmin=:id')
                  ->setParameter(':id',$id)
                  ->andWhere('lp.logArchivo=:archivo')
                  ->setParameter(':archivo',$archivo->getId())
                  ->getQuery()
                  ->getResult();
      if($data)
        $productos[ $archivo->getId() ] = $data;

      $data = null;

      //PedidosCreados
      $data = $em->createQueryBuilder()
                  ->select('lp')
                  ->from('FTPAdministradorBundle:LogProveedores','lp')
                  ->where('lp.logAdmin=:id')
                  ->setParameter(':id',$id)
                  ->andWhere('lp.logArchivo=:archivo')
                  ->setParameter(':archivo',$archivo->getId())
                  ->getQuery()
                  ->getResult();
      if($data)
        $pedidos[ $archivo->getId() ] = $data;

      $data = null;

      //Novedades
      $data = $em->createQueryBuilder()
                  ->select('lp')
                  ->from('FTPAdministradorBundle:LogNovedades','lp')
                  ->where('lp.logAdmin=:id')
                  ->setParameter(':id',$id)
                  ->andWhere('lp.logArchivo=:archivo')
                  ->setParameter(':archivo',$archivo->getId())
                  ->getQuery()
                  ->getResult();
      if($data)
        $novedades[ $archivo->getId() ] = $data;

      $data = null;

    }//end foreach

    return $this->render('FTPAdministradorBundle:SIPproveedores:detalle.html.twig', array(
        'proveedor' => $proveedor,
        'archivos' => $archivos,
        'estructura' => $estructuraArchivos,
        'productos' => $productos,
        'pedidos' => $pedidos,
        'novedades' => $novedades
      ));
  }


  public function cargarPedidosAction($path=null,$session=null,$archivo=null,$ruta=null){

    $pedidosCSV = $this->get('procesarArchivos');
    $pedidosCSV->inicioCargaProveedores();
 
  }


}