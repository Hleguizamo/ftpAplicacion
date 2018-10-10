<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;



/**
 * LogAdmin controller.
 *
 * @Route("/logadmin")
 */
class LogAdministradorController extends Controller{


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
        
        return $this->render('FTPAdministradorBundle:LogAdministrador:index.html.twig', array(
            'permisos'=>$session->get('permisos'),
            'modulo'=>'logAdmin',
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
            $entities = $em->createQuery(' SELECT la.id,la.tiempo,la.actividad,la.ip,a.nombre  FROM FTPAdministradorBundle:LogAdministrador la LEFT JOIN FTPAdministradorBundle:Administrador a WITH a.id=la.idAdministrador  '.$where.' ORDER BY '.$OrdenCampo.' '.$OrdenTipo);
            $entities->setMaxResults($rows);
            $entities->setFirstResult($paginacion);
            $entities = $entities->getResult(); 
            $Contador=$em->createQuery(' SELECT COUNT(la.id) AS contador FROM FTPAdministradorBundle:LogAdministrador la LEFT JOIN FTPAdministradorBundle:Administrador a WITH a.id=la.idAdministrador '.$where)->getSingleResult();
            $numRegistros = $Contador['contador'];
            $totalPagina = ceil($numRegistros / $rows);            
            $response = new Response();
            $response->setStatusCode(200);
            //dump($entities);exit;
            $response->headers->set('Content-Type', 'text/xml');
            return $this->render('FTPAdministradorBundle:LogAdministrador:index.xml.twig', array(
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


  public function exportAllCsvAction(Request $request){
    $session=$request->getSession();
    $response = new Response();
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment;filename="Registro_administradores.csv"');

    $util=$this->get('utilidadesAdministrador');
    $util->registralog('2.1 Exportar actividad de administradores a csv.  ',$session->get('administradorId'));  

    $busqueda = $this->get('busquedaAdministrador');
    $where=$busqueda->busqueda();
    $OrdenTipo = $request->get('sord');
    $OrdenCampo = $request->get('sidx');

    $em=$this->getDoctrine()->getManager();
    $entities = $em->createQuery(' SELECT la.id,la.tiempo,la.actividad,la.ip,a.nombre  FROM FTPAdministradorBundle:LogAdministrador la LEFT JOIN FTPAdministradorBundle:Administrador a WITH a.id=la.idAdministrador  '.$where.' ORDER BY '.$OrdenCampo.' '.$OrdenTipo)->getResult();
   
       return $this->render('FTPAdministradorBundle:LogAdministrador:csvLogAdmin.html.twig', array('entities' => $entities), $response);
    }
}