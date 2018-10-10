<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FTP\AdministradorBundle\Entity\LogProveedores;


/**
 * LogProveedor controller.
 *
 * @Route("/log_proveedores")
 */
class LogProveedoresController extends Controller
{

  
   /**
    * Redirecciona a la vista inicial de logproveedores.
    * @param object $request Objeto peticion de Symfony 2.6.
    * @return vista principal módulo logproveedores.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    * @category FTP\LogProveedores
    */

    public function indexAction(Request $request){

        $session=$request->getSession();
        
        return $this->render('FTPAdministradorBundle:LogProveedores:index.html.twig', array(
            'permisos'=>$session->get('permisos'),
            'modulo'=>'log_proveedores',
            'nombre'=>$session->get('nombre')
        )); 
    }

/**
* Devuelve una respuesta en xml con todos los logs registrados.
* @param object $request Objeto peticion de Symfony 2.6.
* @return Objeto xml con logs.
* @author Julián casas <j.casas@waplicaciones.com.co>
* @since 2.6
* @category FTP\LogProveedores
*/

  public function xmlAction(Request $request){

         if ($request->isXmlHttpRequest()){

            $busqueda = $this->get('busquedaAdministrador');
            $where=$busqueda->busqueda();
            $filtroBusqueda=' ';
            $ordenTipo = $request->get('sord');
            $ordenCampo = $request->get('sidx');
            $rows = $request->get('rows');
            $pagina = $request->get('page');
            $paginacion = ($pagina * $rows) - $rows;
            $em = $this->getDoctrine()->getManager();
            $entities = $em->createQuery(' SELECT lp.id,lp.nombreArchivo,lp.transacciones,lp.codigoDrogueria,lp.estado,lp.codigoPedido,lp.totalPedido,lp.numProductos,lp.fechaConfirmado,lp.proveedor FROM FTPAdministradorBundle:LogProveedores lp '.$where.' ORDER BY '.$ordenCampo.' '.$ordenTipo);
            $entities->setMaxResults($rows);
            $entities->setFirstResult($paginacion);
            $entities = $entities->getResult(); 
            $contador=$em->createQuery(' SELECT COUNT(lp.id) AS contador FROM FTPAdministradorBundle:LogProveedores lp '.$where)->getSingleResult();
            $numRegistros = $contador['contador'];
            $totalPagina = ceil($numRegistros / $rows);            
            $response = new Response();
            $response->setStatusCode(200);
            //dump($entities);exit;
            $response->headers->set('Content-Type', 'text/xml');
            return $this->render('FTPAdministradorBundle:LogProveedores:index.xml.twig', array(
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
* @category FTP\LogProveedores
*/


  public function exportAllCsvAction(Request $request){
    $session=$request->getSession();
    $response = new Response();
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment;filename="Registro_proveedores.csv"');

    $util=$this->get('utilidadesAdministrador');
    $util->registralog('4.1 Exportar registro de transferencias csv.  ',$session->get('administradorId'));  

    $busqueda = $this->get('busquedaAdministrador');
    $where=$busqueda->busqueda();
    $OrdenTipo = $request->get('sord');
    $OrdenCampo = $request->get('sidx');

    $em=$this->getDoctrine()->getManager();
    $entities = $em->createQuery(' SELECT lp.nombreArchivo,lp.transacciones,lp.codigoDrogueria,lp.estado,lp.codigoPedido,lp.totalPedido,lp.numProductos,lp.fechaConfirmado,lp.proveedor FROM FTPAdministradorBundle:LogProveedores lp  '.$where.' ORDER BY '.$OrdenCampo.' '.$OrdenTipo)->getResult();

    return $this->render('FTPAdministradorBundle:LogProveedores:csvLogProveedores.html.twig', array('entities' => $entities), $response);

  }
}
