<?php

namespace FTP\AdministradorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use FTP\AdministradorBundle\Command\MyCommand;
use Symfony\Component\Console\Application;

use FTP\AdministradorBundle\Entity\PedidoDescripcion;
use FTP\AdministradorBundle\Entity\Pedido;
use FTP\AdministradorBundle\Entity\Proveedores;
use FTP\AdministradorBundle\Entity\EstadoFactura;

use FTP\AdministradorBundle\Entity\sipproveedores\Proveedor;
use FTP\AdministradorBundle\Entity\sipproveedores\Transferencista;
use FTP\AdministradorBundle\Entity\sipproveedores\Inventario;
use FTP\AdministradorBundle\Entity\sipproveedores\InventarioKits;
use FTP\AdministradorBundle\Entity\sipproveedores\DescripcionPedido;
use FTP\AdministradorBundle\Entity\sipproveedores\Pedidos;
use FTP\AdministradorBundle\Entity\sipproveedores\InventarioPrepacks;

/**
 *
 * @Route("/proveedor")
 */
class ProveedorController extends Controller
{

     private $message='';

    /**
        * Redirecciona a la vista inicial de Proveedor.
        * @param object $request Objeto peticion de Symfony 2.6.
        * @return vista principal mÃ³dulo logadmin.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    public function indexAction(Request $request){

        $session=$request->getSession();
        return $this->render('FTPAdministradorBundle:Proveedor:index.html.twig', array(
            'permisos'=>$session->get('permisos'),
            'modulo'=>'proveedor',
            'nombre'=>$session->get('nombre')
        )); 
    }

    /**
        * Devuelve una respuesta en xml con todos los logs registrados.
        * @param object $request Objeto peticion de Symfony 2.6.
        * @return Objeto xml con logs.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
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
            $entities = $em->createQuery(' SELECT t FROM FTPAdministradorBundle:Proveedores t '.$where.' ORDER BY '.$OrdenCampo.' '.$OrdenTipo);
            $entities->setMaxResults($rows);
            $entities->setFirstResult($paginacion);
            $entities = $entities->getResult(); 
            $contador=$em->createQuery(' SELECT COUNT(t.id) AS contador FROM FTPAdministradorBundle:Proveedores t '.$where)->getSingleResult();
            $numRegistros = $contador['contador'];
            $totalPagina = ceil($numRegistros / $rows);            
            $response = new Response();
            $response->setStatusCode(200);
            //dump($entities);exit;
            $response->headers->set('Content-Type', 'text/xml');
            return $this->render('FTPAdministradorBundle:Proveedor:index.xml.twig', array(
                'entities' => $entities,'numRegistros' => $numRegistros,
                'maxPagina' => $totalPagina,'pagina' => $pagina), $response);
        }//fin if

    }  

    /**
        * Redirecciona a la vista del formulario para crear un proveedor.
        * @param object $request Objeto peticion de Symfony 2.6.
        * @return 
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    public function newAction(){

        return $this->render('FTPAdministradorBundle:Proveedor:new.html.twig');
    }

    /**
        * Función que verifica la disponibilidad de la carpeta raíz
        * @param String con el valor de la carpeta.
        * @return  Boolean 
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
    */
    public function disponibilidadCarpetaRaiz($carpetaRaiz){

        $em=$this->getDoctrine()->getManager();

        $carpetasCreadas=$em->createQueryBuilder()
        ->select('p.carpetaConvenios carpeta')
        ->from('FTPAdministradorBundle:Proveedores','p')
        ->getQuery()
        ->getArrayResult();

        foreach ($carpetasCreadas as $value) {

            $carpeta=explode('/',$value['carpeta']);
            $carpeta=$carpeta[0];
            if($carpeta == $carpetaRaiz){
                return false;
            }
            
        }//fin foreach

        return true;
    }//fin function


    /**
        * AcciÃ³n que recibe los datos del nevo proveedor.
        * @param object $request Objeto peticion de Symfony 2.6.
        * @return  JSON array con el estado de la transacciÃ³n.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function createAction(Request $request){


        $session=$request->getSession();
        $em=$this->getDoctrine()->getManager();
        
        $json=array();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try{
            //campos vacios.
            if($request->get('proveedor')==''||
               $request->get('codigoProveedor')==''||
               $request->get('nit')==''||
               $request->get('encargado')==''||
               $request->get('emailEncargado')==''){
               $json['respuesta']=99;
               $response->setContent(json_encode($json));
               return $response;
            } 
            //disponibilidad del nit.
            
            $data=$em->createQueryBuilder()->select('COUNT(p.id) contador')
            ->from('FTPAdministradorBundle:Proveedores','p')
            ->where('p.nitProveedor=?1')
            ->setParameter(1,$request->get('nit'))
            ->getQuery()
            ->getOneOrNullResult();

            if($data['contador']>=1){ 
              $json['respuesta']=3;
              $response->setContent(json_encode($json));
              return $response;
            }

            //disponibilidad del codigo proveedor.
            
            $data=$em->createQueryBuilder()->select('COUNT(p.id) contador')
            ->from('FTPAdministradorBundle:Proveedores','p')
            ->where('p.codigoProveedor=?1')
            ->setParameter(1,$request->get('codigoProveedor'))
            ->getQuery()
            ->getOneOrNullResult();

            if($data['contador']>=1){ 
              $json['respuesta']=4;
              $response->setContent(json_encode($json));
              return $response;
            }

            //Crear las rutas para la carpete FTP del Proveedor.

            $carperaRaiz=$request->get('carpetaRaiz');
            $carpetaRaiz=strtolower( str_replace(' ', '',$carperaRaiz));

            $carpetaRaiz= preg_replace("/[^a-zA-Z0-9\-\_\s]/", "", $carpetaRaiz);
            $carpetaTransferencias=$carpetaRaiz.'/transferencias';
            $carpetaConvenios=$carpetaRaiz.'/convenios';

            if(!$this->disponibilidadCarpetaRaiz($carpetaRaiz)){
                $json['respuesta']=5;
                $response->setContent(json_encode($json));
                return $response;
          
            }


            $proveedores=new Proveedores();

            $proveedores->setCodigoProveedor($request->get('codigoProveedor'));
            $proveedores->setProveedor($request->get('proveedor'));
            $proveedores->setNitProveedor($request->get('nit'));
            $proveedores->setEstadoTransferencia($request->get('estadoTransferencia'));
            $proveedores->setEstadoConvenios($request->get('estadoConvenios'));
            $proveedores->setEncargadoProveedor($request->get('encargado'));
            $proveedores->setEmailEncargado($request->get('emailEncargado'));
            $proveedores->setEstado($request->get('estado'));
            $proveedores->setCarpetaTransferencista($carpetaTransferencias);
            $proveedores->setCarpetaConvenios($carpetaConvenios);
            $em->persist($proveedores);
            $em->flush();

            //registrar losg
            $util=$this->container->get('utilidadesAdministrador');
            $util->registralog('3.1 Creacion de proveedor[ '.$request->get('proveedor').' ] ',$session->get('administradorId'));

            $json['respuesta']=2;
            $response->setStatusCode(200);
        }catch(Exception $e){
            $response->setStatusCode(500);
        }

        return $response->setContent(json_encode($json));
    }


    /**
        * Genera la vista junto co la entidad Proveedor a editar.
        * @param object $request Objeto peticion de Symfony 2.6.
        * @return vista del formulario de ediciÃ³n con los datos a editar.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FacturaciÃ³n\Proveedor
        * @Template ("FTPAdministradorBundle:Proveedor:edit.html.twig")
    */
    public function editAction(Request $request,$id){

        $em=$this->getDoctrine()->getManager();

        $proveedor=$em->createQueryBuilder()
        ->select('p.id,p.proveedor,p.codigoProveedor,p.nitProveedor,p.estadoTransferencia,'
            .'p.estadoConvenios,p.encargadoProveedor,p.emailEncargado,p.estado,p.carpetaConvenios,p.carpetaTransferencista')
        ->from('FTPAdministradorBundle:Proveedores','p')
        ->where('p.id=?1')
        ->setParameter(1,$id)
        ->getQuery()
        ->getSingleResult();

        
        $carpetaRaiz=explode("/", $proveedor['carpetaConvenios']);
        $carpetaRaiz=$carpetaRaiz[0];
        return array(
            'proveedor'=> $proveedor,
            'carpetaRaiz'=>$carpetaRaiz
        );
    }

    /**
        * Actualiza los datos del proveedor segÃºn los parÃ¡metros enviados.
        * @param id del administrador.Object $request Objeto peticion de Symfony 2.6.
        * @return json con el estado final de la tarea.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    
    public function updateAction(Request $request){
        $session=$request->getSession();
        $em=$this->getDoctrine()->getManager(); 

        $json=array();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try{
            //campos vacios.
            if($request->get('proveedor')==''||
               $request->get('codigoProveedor')==''||
               $request->get('nit')==''||
               $request->get('encargado')==''||
               $request->get('emailEncargado')==''){
               $json['respuesta']=99;
               $response->setContent(json_encode($json));
               return $response;
            } 
           
           

            if(strcmp($request->get('nit'),$request->get('nitActual')) !=0){
                //disponibilidad del nit.
                
                $data=$em->createQueryBuilder()
                ->select('COUNT(p.id) contador')
                ->from('FTPAdministradorBundle:Proveedores','p')
                ->where('p.nitProveedor=?1')
                ->setParameter(1,$request->get('nit'))
                ->getQuery()
                ->getOneOrNullResult();
                if($data['contador']>=1){ 
                  $json['respuesta']=3;
                  $response->setContent(json_encode($json));
                  return $response;
                }
            }
            //disponibilidad del codigo proveedor.
            if(strcmp($request->get('codigoProveedor'),$request->get('codigoActual')) !=0){

                $data=$em->createQueryBuilder()
                ->select('COUNT(p.id) contador')
                ->from('FTPAdministradorBundle:Proveedores','p')
                ->where('p.codigoProveedor=?1')
                ->setParameter(1,$request->get('codigoProveedor'))
                ->getQuery()
                ->getOneOrNullResult();
                if($data['contador']>=1){ 
                  $json['respuesta']=4;
                  $response->setContent(json_encode($json));
                  return $response;
                }
            }

            //verificar disponibilidad de la carpeta raíz
            $capetaActula=$request->get('carpetaRaizActual');
            $carpetaRaiz=$request->get('carpetaRaiz');

            if($capetaActula != $carpetaRaiz){

                if(!$this->disponibilidadCarpetaRaiz($carpetaRaiz)){

                    $json['respuesta']=5;
                    $response->setContent(json_encode($json));
                    return $response;

                }//fin if
            }//fin if

            $carpetaRaiz=strtolower( str_replace(' ', '',$carpetaRaiz));
            $carpetaRaiz= preg_replace("/[^a-zA-Z0-9\-\_\s]/", "", $carpetaRaiz);
            $carpetaTransferencias=$carpetaRaiz.'/transferencias';
            $carpetaConvenios=$carpetaRaiz.'/convenios';

            


            //actualizar datos del proveedor

            $em->createQueryBuilder()
            ->update('FTPAdministradorBundle:Proveedores','p')
            ->set('p.proveedor','?1')
            ->set('p.codigoProveedor','?2')
            ->set('p.nitProveedor','?3')
            ->set('p.estadoTransferencia','?4')
            ->set('p.estadoConvenios','?5')
            ->set('p.encargadoProveedor','?6')
            ->set('p.emailEncargado','?7')
            ->set('p.estado','?8')
            ->where('p.id=?9')
            ->set('p.carpetaTransferencista',':transferencista')
            ->set('p.carpetaConvenios',':convenos')
            ->setParameter(1,$request->get('proveedor'))
            ->setParameter(2,$request->get('codigoProveedor'))
            ->setParameter(3,$request->get('nit'))
            ->setParameter(4,$request->get('estadoTransferencia'))
            ->setParameter(5,$request->get('estadoConvenios'))
            ->setParameter(6,$request->get('encargado'))
            ->setParameter(7,$request->get('emailEncargado'))
            ->setParameter(8,$request->get('estado'))
            ->setParameter(9,$request->get('id'))
            ->setParameter('transferencista',$carpetaTransferencias)
            ->setParameter('convenos',$carpetaConvenios)
            ->getQuery()
            ->execute();


            $response->setStatusCode(200);
            $util=$this->container->get('utilidadesAdministrador');
            $util->registralog('3.2 Actualizacion de Proveedor[ '.$request->get('proveedor').' ] ',$session->get('administradorId'));
        }catch(Exception $e){
            $response->setStatusCode(500); 
        }

          $json['respuesta']=2;
           $response->setContent(json_encode($json));
        return $response;
    }


    /**
        * Elimina el proveedor seleccionado.
        * @param id del administrador.Object $request Objeto peticion de Symfony 2.6.
        * @return json y/o cÃ³digo de estatus con el estado final de la tarea.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    public function deleteAction(Request $request){
        $session=$request->getSession();
        $em=$this->getDoctrine()->getManager();
        $json=array();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        try{

            $em->createQueryBuilder()
            ->delete('FTPAdministradorBundle:Proveedores','p')
            ->where('p.id=?1')
            ->setParameter(1,$request->get('id'))
            ->getQuery()
            ->execute();

            $util=$this->container->get('utilidadesAdministrador');
            $util->registralog('3.4 Se elimina el proveedor[ '.$request->get('proveedor').' ] ',$session->get('administradorId'));

            $response->setStatusCode(200);
            $json['respuesta']=1;
        }catch(Exception $e){
            $response->setStatusCode(500); 
        }
        $response->setContent(json_encode($json));
        return $response;  
    }

    /**
        * Renderiza a la vista con el formulario para procesar a Convenios y Transferencistas el proveedor seleccionado.
        * @param id del proveedor.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
        * @Template ("FTPAdministradorBundle:Proveedor:proveedorForm.html.twig")
    */
    public function proveedorFormAction($id){

        $em=$this->getDoctrine()->getManager();

        $proveedor=$em->createQueryBuilder()
        ->select('p.codigoProveedor,p.nitProveedor,p.estadoTransferencia,p.estadoConvenios,p.id,p.carpetaTransferencista,p.carpetaConvenios')
        ->from('FTPAdministradorBundle:Proveedores','p')
        ->where('p.nitProveedor=?1')
        ->setParameter(1,$id)
        ->getQuery()
        ->getOneOrNullResult();
        
        return array('proveedor'=>$proveedor);
    }

     /**
        * AcciÃ³n que genera los archivos de texto plano de Convenios para el proveedor seleccionado,
        * carga pedidos a Convenios recuperando un CSV.
        * genera archivo plano con estado final de la carga
        * y los envÃ­a vÃ­a FTP a una ruta remota para el proveedor.
        * @param Object $request Objeto peticion de Symfony 2.6.
        * @return json y/o cÃ³digo de estatus con el estado final de la acciÃ³n.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    public function conveniosProveedorAction(Request $request){
        $session=$request->getSession();
        set_time_limit (0);
        ini_set('memory_limit', '512M');
        $emc=$this->getDoctrine()->getManager('convenios');
        $em=$this->getDoctrine()->getManager();

        //NIT proveedor.
        $nitProveedor=$request->get('data');

        // Datos proveedor.
        $proveedor=$em->createQueryBuilder()
                ->select('p.id,p.nitProveedor nit,p.proveedor,p.carpetaConvenios,p.codigoProveedor')
                ->from('FTPAdministradorBundle:Proveedores','p')
                ->where('p.nitProveedor=?1')
                ->setParameter(1,$nitProveedor)
                ->getQuery()
                ->getOneOrNullResult();
       
        //tipo de respuesta.
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        //Variables de entorno.
        $informe=array();
        $textoPlano=array();
        $dir = $this->get('kernel')->getRootDir().'/../web/';

        //servicios
        $tarea=$this->container->get('generarArchivos');

        if($proveedor){

            $proveedoresConvenios = $emc->getRepository('FTPAdministradorBundle:Proveedor')->findAll();
            $arrayProveedoresConvenios = array();
            foreach($proveedoresConvenios as $datosProveedor){
                $arrayProveedoresConvenios[$datosProveedor->getCodigoCopidrogas()] = $datosProveedor->getId();
            }

            //$informe=$this->procesarPedidoMultipleAction($proveedor,$proveedor['carpetaConvenios'].'/',$session); //Recuperar CSV y generar pedido en Convenios.
            $informe=$this->procesarPedidoMultipleAction($proveedor,$proveedor['carpetaConvenios'].'/',$session, $arrayProveedoresConvenios); //Recuperar CSV y generar pedido en Convenios.
            $textoPlano=$tarea->asociadoConvenios($proveedor['codigoProveedor'],$proveedor['carpetaConvenios'].'/inventario/',$dir); //Generar archivo de texto plano con informaciÃ³n de los asociados y cargarlo a la carpeta de Convenios por FTP.
            $resultadoFinal = $this->renderView('FTPAdministradorBundle:Proveedor:informeUnProveedor.html.twig', array(
                'informes'=>$informe['informe'],
                'logs' =>$informe['log'],
                'sinArchivos'=>$informe['sinArchivos'],
                'inventario'=> $textoPlano
            ));
            
        }else{
          $informe['informe']['error']='No se encontraron datos del proveedor '.$nitProveedor.' en Convenios.Porfavor verifique el NIT.';
        }
        $data=array('textoPlano'=>$textoPlano,'informe'=>$resultadoFinal,'app'=>'convenios');
        $response->setContent(json_encode($data));
        return $response;
    }

    /**
        * Renderiza a la vista con el formulario para procesar a Convenios y Transferencistas para todos los proveedores disponibles.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
        * @Template ("FTPAdministradorBundle:Proveedor:proveedoresForm.html.twig")
    */
    public function proveedoresFormAction(){
        return array();
    }

    /**
        * Modifica el estado a true o false de los campos estado_trasferencia y estado_convenios.
        * @param Object $request Objeto peticion de Symfony 2.6.
        * @return json y/o codigo de estatus con el estado final de la tarea.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function actualizarAction(Request $request){

        $em=$this->getDoctrine()->getManager();
        // dump($_POST);exit;
        $json=array();
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $campo=$request->get('campo');
        $estado=$request->get('estado');
        $proveedor=$request->get('codigoProveedor');
        if($campo==1){
            $campo='p.estadoTransferencia';
        }
        if($campo==2){
            $campo='p.estadoConvenios';
        }

        try{

            //actualizar datos del proveedor
            $em->createQueryBuilder()
            ->update('FTPAdministradorBundle:Proveedores','p')
            ->set($campo,'?1')
            ->where('p.codigoProveedor=?2')
            ->setParameter(1,$estado)
            ->setParameter(2,$proveedor)
            ->getQuery()
            ->execute();

            $response->setStatusCode(200);
            $json['respuesta']=1;
        }catch(Exception $e){
            $response->setStatusCode(500); 
        }
        $response->setContent(json_encode($json));
        return $response; 
    }


    public function transferenciaAction(Request $request){
        //echo "entra";exit();
        $response=new Response;
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(200);

        $codigoProveedor=$request->get('data');

        $inventario = $this->cargarInventario( $codigoProveedor ); 

        $sipproveedores = $this->get('procesarArchivos');
        //para llamar las funciones que se neseciten

                
        $json=array();

        $mensajeInterfaz = $sipproveedores->inicioCargaProveedores(true, $codigoProveedor);
        $json['template']=$this->renderView('FTPAdministradorBundle:Proveedor:informeTodosProveedorTrans.html.twig', array(
            'mensajeInterfaz' => $mensajeInterfaz,
            'inventario' => $inventario
          ));
        $json['app'] = "transferencista";

        //$json['template']=$sipproveedores->inicioCargaProveedores(true, $codigoProveedor);
        
        
        $response->setContent(json_encode($json));

        return $response;
               
    }

    private function cargarInventario( $codigoProveedor ){

        $inventario = true; 

        try{

            $dir = $this->get('kernel')->getRootDir().'/../web/';

            $tarea=$this->container->get('generarArchivos');

            $em = $this->getDoctrine()->getManager();

            $proveedor=$em->createQueryBuilder()
                        ->select('p')
                        ->from('FTPAdministradorBundle:Proveedores','p')
                        ->where('p.codigoProveedor=?1')
                        ->setParameter(1,$codigoProveedor)
                        ->getQuery()
                        ->getOneOrNullResult();

            //conexion a FTP.
            $conexionFTP=@ftp_connect($this->container->getParameter('host_ftp'),$this->container->getParameter('port'));
            @ftp_login($conexionFTP,$this->container->getParameter('user_ftp'),$this->container->getParameter('pass_ftp'));

            if($proveedor->getCarpetaTransferencista()){  
                        
                //Validamos si el directorio existe
                if(!@ftp_chdir($conexionFTP,$proveedor->getCarpetaTransferencista())){
                    $raiz=explode('/',$proveedor->getCarpetaTransferencista());
                    if(!@ftp_chdir($conexionFTP,$raiz[0])){
                      @ftp_mkdir($conexionFTP,$raiz[0]);
                      @ftp_chdir($conexionFTP,$raiz[0]);
                    }
                    if(isset($raiz[1])){
                        if(!@ftp_chdir($conexionFTP,$raiz[1])){
                          @ftp_mkdir($conexionFTP,$raiz[1]);
                          @ftp_chdir($conexionFTP,$raiz[1]);
                        }
                    }else{
                        if(!@ftp_chdir($conexionFTP,'transferencias')){
                          @ftp_mkdir($conexionFTP,'transferencias');
                          @ftp_chdir($conexionFTP,'transferencias');
                        }
                    }

                    if(!@ftp_chdir($conexionFTP,'procesados')){
                        @ftp_mkdir($conexionFTP,'procesados');
                    }else{
                        @ftp_chdir($conexionFTP,'../');
                    }

                    if(!@ftp_chdir($conexionFTP,'inventario')){
                        @ftp_mkdir($conexionFTP,'inventario');
                    }else{
                        @ftp_chdir($conexionFTP,'../');   
                    }         
                }
                $ruta=ftp_pwd($conexionFTP);
                

                //archivo asociados.
                $asociados=$tarea->asociados($ruta.'/inventario/',$codigoProveedor,$dir);

                //generar inventario
                $inventario=$tarea->inventario($ruta.'/inventario/',$codigoProveedor,$dir);

                //bonificaciones
                $bonificaciones=$tarea->bonificaciones($ruta.'/inventario/',$codigoProveedor,$dir);

                //kits
                $kits=$tarea->kits($ruta.'/inventario/',$codigoProveedor,$dir);

                //Detalle kits
                $detallekits=$tarea->kitsDetalle($ruta.'/inventario/',$codigoProveedor,$kits['kits'],$dir);

            }//end if

        }catch(\Exception $e){

             $inventario = false;
            //dump($e->getMessage());exit;

        }

        return $inventario;


    }//end function

    /**
        * AcciÃ³n que genera los archivos de texto plano de Transferencias y los carga vÃ­a FTP.
        * @param Object $request Objeto peticion de Symfony 2.6.
        * @return json y/o cÃ³digo de estatus con el estado final de la tarea.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    public function transferenciaAnteriorAction(Request $request){
        exit();
        $emsp=$this->getDoctrine()->getManager('sipproveedores');
        $em=$this->getDoctrine()->getManager();
        $session=$request->getSession();

        set_time_limit (0);
        ini_set('memory_limit', '1024M');

        // Tipo de respuesta
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        //Variable de entorno
        $codigoProveedor=$request->get('data');
        $respuesta=array();
        $logFTP=array();
        $path=false;
        $arrayArchivosAProcesar=array();  
        $estado=false; 
        $archivo=false;
        $consecutivos=false;
        $datosPedido=false;
        $productosInsertados=false;
        $noLeidas=false;
        $directorio=false;
        $estado=false; 
        $estructura=false;
        $arrayArchivos=array();
        $inventarioTxt=array();
        $arrayFTP=array();
        $carpeta=false;
        $dir = $this->get('kernel')->getRootDir().'/../web/';
        $logAdmin=$this->container->get('utilidadesAdministrador');

        $tarea=$this->container->get('generarArchivos');

        $proveedor=$em->createQueryBuilder()
                    ->select('p')
                    ->from('FTPAdministradorBundle:Proveedores','p')
                    ->where('p.codigoProveedor=?1')
                    ->setParameter(1,$codigoProveedor)
                    ->getQuery()
                    ->getOneOrNullResult();

        //conexion a FTP.
        $conexionFTP=@ftp_connect($this->container->getParameter('host_ftp'),$this->container->getParameter('port'));
        @ftp_login($conexionFTP,$this->container->getParameter('user_ftp'),$this->container->getParameter('pass_ftp'));

        if($proveedor->getCarpetaTransferencista()){  
                    
            //Validamos si el directorio existe
            if(!@ftp_chdir($conexionFTP,$proveedor->getCarpetaTransferencista())){
                $raiz=explode('/',$proveedor->getCarpetaTransferencista());
                if(!@ftp_chdir($conexionFTP,$raiz[0])){
                  @ftp_mkdir($conexionFTP,$raiz[0]);
                  @ftp_chdir($conexionFTP,$raiz[0]);
                }
                if(isset($raiz[1])){
                    if(!@ftp_chdir($conexionFTP,$raiz[1])){
                      @ftp_mkdir($conexionFTP,$raiz[1]);
                      @ftp_chdir($conexionFTP,$raiz[1]);
                    }
                }else{
                    if(!@ftp_chdir($conexionFTP,'transferencias')){
                      @ftp_mkdir($conexionFTP,'transferencias');
                      @ftp_chdir($conexionFTP,'transferencias');
                    }
                }

                if(!@ftp_chdir($conexionFTP,'procesados')){
                    @ftp_mkdir($conexionFTP,'procesados');
                }else{
                    @ftp_chdir($conexionFTP,'../');
                }

                if(!@ftp_chdir($conexionFTP,'inventario')){
                    @ftp_mkdir($conexionFTP,'inventario');
                }else{
                    @ftp_chdir($conexionFTP,'../');   
                }         
            }
            $ruta=ftp_pwd($conexionFTP);
            

            //archivo asociados.
            $asociados=$tarea->asociados($ruta.'/inventario/',$codigoProveedor,$dir);

            //generar inventario
            $inventario=$tarea->inventario($ruta.'/inventario/',$codigoProveedor,$dir);

            //bonificaciones
            $bonificaciones=$tarea->bonificaciones($ruta.'/inventario/',$codigoProveedor,$dir);

            //kits
            $kits=$tarea->kits($ruta.'/inventario/',$codigoProveedor,$dir);

            //Detalle kits
            $detallekits=$tarea->kitsDetalle($ruta.'/inventario/',$codigoProveedor,$kits['kits'],$dir);

            $inventarioTxt[]=$asociados;
            $inventarioTxt[]=$inventario;
            $inventarioTxt[]=$bonificaciones;
            $inventarioTxt[]=$kits['archivo'];
            $inventarioTxt[]=$detallekits;  

            if(!$directorio){
                //Recuperamos los archivos disponibles.
                $documentos = ftp_nlist($conexionFTP, ".");

                
                foreach ($documentos as $file) {
                    if(substr($file, -3)=='csv'||substr($file, -3)=='CSV'||
                       substr($file, -3)=='TXT'||substr($file, -3)=='txt' ){
                        $arrayArchivosAProcesar[]=$file;
                    }
                } 
                unset($documentos);
                if($arrayArchivosAProcesar){
                    $contPeidos=0;
                    foreach($arrayArchivosAProcesar as $archivo){

                        @ftp_get($conexionFTP,$archivo,$archivo, FTP_BINARY);
                        $pedidos=$this->pedidoSipProveedores($dir,$session,$archivo, $ruta);
                        $logFTP[$archivo][]=$tarea->transaccionFTP($dir.$archivo,$archivo,$ruta.'/procesados/');
                       // $logFTP[$archivo][]=$tarea->logSipProveedores($archivo,$pedidos,$ruta.'/procesados/',$dir);
                        
                        @ftp_delete($conexionFTP,$archivo);
                        if(isset($pedidos['consecutivos']))
                          $contPeidos+=count($pedidos['consecutivos']);
                        $respuesta['informe'][$archivo]=$pedidos;
                        $arrayFTP=$logFTP;
                        $estado=true; 
                    } //fin foreach 
                    $this->registrarArchivos($arrayArchivosAProcesar,$codigoProveedor,$session);
                    //Registrar información de último pedido cargado, cantidad y última transacción con SipPRoveedores.          

                    if($contPeidos>0){
                        $this->updateProveedor($codigoProveedor,$contPeidos,2);
                        $logAdmin->registralog('3.1.4 Cargar '.$contPeidos.' pedido(s) a SipProveedores. Proveedor['.$proveedor->getProveedor().'] ',$session->get('idAdministrador')); 
                    }
                }   
                unset($arrayArchivosAProcesar);

                if($estado){
                    foreach ($respuesta['informe'] as $k=>$value) {  

                        if(isset($value['consecutivos'])){
                            $consecutivos[$k]=$value['consecutivos'];
                            unset($respuesta['informe'][$k]['consecutivos']);
                        } 
                        if(isset($value['datosPedido'])){
                            $datosPedido[$k]=$value['datosPedido'];
                            unset($respuesta['informe'][$k]['datosPedido']);
                        } 
                        if(isset($value['insertados'])){
                            $productosInsertados[$k]=$value['insertados'];
                            unset($respuesta['informe'][$k]['insertados']);
                        }
                        if(isset($value['noLeidas'])){
                            $noLeidas[$k]=$value['noLeidas'];
                            unset($respuesta['informe'][$k]['noLeidas']);
                        }
                        if(isset($value['errorArchivo'])){
                           $estructura[$k]=$value['errorArchivo'];
                            unset($respuesta['informe'][$k]['errorArchivo']);
                        }
                                          
                        $arrayArchivos[$k]=$k;
                    }
                }
            }
        }else{
             $carpeta='La carpeta para el proveedor ['.$proveedor->getProveedor().']. No se encuentra registrada en el sistema.';
        }    

        if($respuesta['informe']){

            $respuesta['template']=$this->renderView('FTPAdministradorBundle:Proveedor:informeUnProveedorTrans.html.twig', array(
                    'errorArchivo'=>$estructura,
                    'archivos'=>$arrayArchivos,
                    'consecutivos'=>$consecutivos,
                    'datosPedido'=>$datosPedido,
                    'productosInsertados'=>$productosInsertados,
                    'noLeidas'=>$noLeidas,
                    'estadoFTP'=>$arrayFTP,
                    'inventarioTxt'=>$inventarioTxt,
                    'carpetas'=>$carpeta
            ));

            $this->enviarCorreoProveedor($respuesta['template']);

        }else{
            $mensaje='<h1>Se inicio la carga de pedidos a SIPProveedores. El proceso se completó correctamente.</h1>';
            $this->enviarCorreoProveedor($mensaje); 
        }

        
       
        $respuesta['app']='transferencista';
        $respuesta['estado']=$estado;
        $respuesta['directorio']=$directorio;

        ftp_close($conexionFTP);

        return $response->setContent(json_encode($respuesta));
        
        
    }

    /**
        * AcciÃ³n registra la informaciÃ³n del estado FTP de los archivos.
        * @param {$inventario:array con los archivos generados,$proveedor:nombre del proveedor,$session:informaciÃ³n de la sesiÃ³n actual.}
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function registrarFTP($arrayFTP,$proveedor,$session){
        $tarea=$this->container->get('utilidadesAdministrador');
        if($arrayFTP){
            $archivos='';
            foreach ($arrayFTP as $nombre=>$value) {
                if($archivos!='')
                    $archivos.=',';
                $archivos.=$nombre;      
            }
            $tarea->registralog('3.8  '.$proveedor.' Archivos cargados al FTP ['.$archivos.'] ',$session->get('administradorId')); 
               
        }else{
             $tarea->registralog('3.8  '.$proveedor.'. No se encontraron archivos para procesar y cargar al FTP',$session->get('administradorId')); 
        }
        unset($inventario,$tarea,$proveedor); 
        
    }

    /**
        * AcciÃ³n registra la informaciÃ³n de los archivos no generados porque la estructura es incorrecta.
        * @param {$archivo:array con los archivos con error en su estructura,$proveedor:nombre del proveedor,$session:informaciÃ³n de la sesiÃ³n actual.}
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function registrarEstrucrura($archivo,$proveedor,$session){
        if($archivo){
            $tarea=$this->container->get('utilidadesAdministrador');
            $numArchivos=count($archivo);
            $nombre='';
            foreach ($archivo as $value) {
                if($nombre!='')
                    $nombre.=',';
                $nombre.=substr($value,33);      
            }
            $tarea->registralog('3.7  '.$proveedor.' Error en la estructura del archivo ['.$nombre.'] ('.$numArchivos.') archivos.',$session->get('administradorId')); 
            unset($inventario,$tarea,$proveedor);    
        }
        
    }

    /**
        * AcciÃ³n registra la informaciÃ³n de los archivos pprocesados
        * @param {$archivo:array con los archivos procesados,$proveedor:nombre del proveedor,$session:informaciÃ³n de la sesiÃ³n actual.}
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function registrarArchivos($archivo,$proveedor,$session){
        $tarea=$this->container->get('utilidadesAdministrador');
        if($archivo){
            $numArchivos=count($archivo);
            $nombre='';
            foreach ($archivo as $value) {
                if($nombre!='')
                    $nombre.=',';
                $nombre.=$value;      
            }
            $tarea->registralog('3.6  '.$proveedor.' archivos procesados['.$nombre.'] ('.$numArchivos.') archivos  ',$session->get('administradorId'));     
        }else{
            $tarea->registralog('3.6  No se encontraron archivos para procesar para el proveedor [ '.$proveedor.']',$session->get('administradorId'));     
        }
        unset($inventario,$tarea,$proveedor);
    }
    

    /**
        * AcciÃ³n registra la informaciÃ³n del inventario generado.
        * @param {$inventario:array con los archivos generados,$proveedor:nombre del proveedor,$session:informaciÃ³n de la sesiÃ³n actual.}
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function registrarInventario($inventario,$proveedor,$session){
        $tarea=$this->container->get('utilidadesAdministrador');
        $numArchivos=count($inventario);
        if($inventario){
            $archivos='';
            foreach ($inventario as $value) {
                if($archivos!='')
                    $archivos.=',';
                $archivos.=$value;      
            }

            $tarea->registralog('3.5.1 Inventario generado. Proveedor ['.$proveedor.'] '.$archivos.'.('.$numArchivos.') Archivos',$session->get('administradorId'));     
        }else{
             $tarea->registralog('3.5.2 No se pudo generar el inventario. Proveedor ['.$proveedor.'] ('.$numArchivos.') Archivos',$session->get('administradorId'));   
        }
        unset($inventario,$tarea,$proveedor);
    }
    /**
        * AcciÃ³n que genera los archivos de texto plano de Convenios para cada proveedor disponible y con autorizaciÃ³n,
        * carga inventario de pedidos segÃºn proveedor que se este procesando,
        * genera archivo plano con estado final de la carga
        * y los envÃ­a vÃ­a FTP a una ruta remota para cada proveedor.
        * Se procesan todos los proveedores disponibles y con acceso a convenios true.
        * @param Object $request Objeto peticion de Symfony 2.6.
        * @return json y/o cÃ³digo de estatus con el estado final de la tarea.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    public function conveniosProveedoresAction(Request $request){
            $session=$request->getSession();
            set_time_limit (0);
            ini_set('memory_limit', '1024M');
            //Conexiones a BD.
            $em = $this->getDoctrine()->getManager();
            $emc=$this->getDoctrine()->getManager('convenios');


            //tipo de respuesta.
            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');

            //Variables de entorno.
            $informe=array();
            $json=array();
            $dir = $this->get('kernel')->getRootDir().'/../web/';
            $resultadoFinal='';
            $asociados=array();

            //servicios
            $tarea=$this->container->get('generarArchivos');

            //Recuperar proveedores disponibles.
            $proveedores=$em->createQueryBuilder()
            ->select('p.proveedor,p.nitProveedor,p.estadoConvenios')
            ->from('FTPAdministradorBundle:Proveedores','p')
            ->getQuery()->getResult();


            //Filtrar proveedores habilitados para Convenios.
            $proveedores=$this->estadoConvenios($proveedores);
            $proveedores['inhabilitados']=(isset($proveedores['inhabilitados']))?$proveedores['inhabilitados']:false;


            $proveedoresConvenios = $emc->getRepository('FTPAdministradorBundle:Proveedor')->findAll();
            $arrayProveedoresConvenios = array();
            foreach($proveedoresConvenios as $datosProveedor){
                $arrayProveedoresConvenios[$datosProveedor->getCodigoCopidrogas()] = $datosProveedor->getId();
            }

            //Generar pedido.
            if(isset($proveedores['habilitados'])){
                foreach ($proveedores['habilitados'] as $k=>$v) {

                    // consulta BD FTP
                    $proveedor=$em->createQueryBuilder()
                    ->select('p.id,p.nitProveedor nit,p.proveedor,p.carpetaConvenios, p.codigoProveedor')
                    ->from('FTPAdministradorBundle:Proveedores','p')
                    ->where('p.nitProveedor=?1')
                    ->setParameter(1,$k)
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getOneOrNullResult();

                    if($proveedor){
                        //$informe[$v]=$this->procesarPedidoMultipleAction($proveedor,$proveedor['carpetaConvenios'].'/',$session); //Recuperar CSV y generar pedido en Convenios.
                        $informe[$v]=$this->procesarPedidoMultipleAction($proveedor,$proveedor['carpetaConvenios'].'/',$session, $arrayProveedoresConvenios); //Recuperar CSV y generar pedido en Convenios.
                        $asociados[$v]=$tarea->asociadoConvenios($proveedor['codigoProveedor'],$proveedor['carpetaConvenios'].'/inventario/',$dir); //Generar archivo de texto plano con informaci?n de los asociados y cargarlo a la carpeta de Convenios por FTP.
                    }else{
                        $informe[$v]['informe']['error']='No se encontraron datos del proveedor '.$v.' en Convenios.Porfavor verifique el NIT.';
                    }
                }//fin foreach
                    $resultadoFinal = $this->renderView('FTPAdministradorBundle:Proveedor:informeTodosProveedores.html.twig', array(
                        'informes'=>$informe,
                        'inhabilitados' =>$proveedores['inhabilitados'],
                        'inventarios'=>$asociados
                    ));
            }else{
                 $resultadoFinal = $this->renderView('FTPAdministradorBundle:Proveedor:informeTodosProveedores.html.twig', array(
                        'informes'=>$informe,
                        'inhabilitados' =>$proveedores['inhabilitados'],
                        'inventarios'=>$asociados
                    ));
            }
            $resultado['app']='convenios';
            $resultado['informe'] =$resultadoFinal;

            return $response->setContent(json_encode($resultado));


    }

    /**
        * AcciÃ³n que verifica el estado de los proveedores para acceder a Convenios y los separa en un array con Ã­ndices [habilitado,deshabilitado]
        * @param $proveedores= array de proveedores recuperados por el sistema.
        * @return array con los proveedores habilitados y deshabilitados.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    private function estadoConvenios($proveedores){
        $data=array();
        foreach ($proveedores as $k => $v) {
            if($v['estadoConvenios']){
                $data['habilitados'][$v['nitProveedor']]=$v['proveedor'];
            }else{
                 $data['inhabilitados'][$v['nitProveedor']]=$v['proveedor'];
            }
        }
        return $data;
    }


    /**
        * AcciÃ³n que recupera un archivo CSV y crea pedidos en Convenios segÃºn la informaciÃ³n que contiene el archivo.
        * @param $proveedor[nit,id], NIT del proveedor a procesar junto con el $id.$path, ruta local del archivo a recuperar., $session= SesiÃ³n actual.
        * @return $informe=array(), con el resultado de procesar el archivo.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    public function procesarPedidoMultipleAction($proveedor,$path,$session, $arrayProveedoresFtp) {

        set_time_limit(0);
        ini_set('memory_limit', '2048M');
              
        //conxiones.
        $em=$this->getDoctrine()->getManager();
        $emc=$this->getDoctrine()->getManager('convenios');
        $ems = $this->getDoctrine()->getManager('sipasociados');
        $dir = $this->get('kernel')->getRootDir().'/../web/';
        
        //variables de entorno.

        $drogueriasArray=array();
        $cont=0;
        $pedidosTotales=0;
        $informe=array();
        $productosIngresados=array();
        $insertar=true;
        $totalPedido='';
        $directorio = false;
        $arrayArchivosAProcesar=array();
        $txt=array();
        $sinArchivos=array();
        $noHayDirectorio=false;
        $cantArchivos = 0;
        $pedidosGenerados=array();
        $logRespuesta=array();
        $productosRegistrados=array();//array que guarda la informacion de los productos insertados para enviarlos por correo cuando se confirme el pedido.
        $iteradorProductos=0;
        $clientesConsultados=array();
        $aceptacionesContrato= array();
        $archivosError=array();

        //$output->writeln("Se incia la tarea de convenios");
        //$this->registralog("Se incia la tarea de convenios");

        //servicios
        $tarea=$this->container->get('utilidadesAdministrador');
        $tarea2=$this->container->get('generarArchivos');

        $utilidades=$this->get('utilidadesAdministrador');
          

        $emcConect = $emc->getConnection();

        $sqlTransferencista = "SELECT t.id, t.nombres AS transfNombres, p.nombre AS provNombre 
        FROM proveedor p 
        JOIN transferencista t ON p.id = t.proveedor_id 
        WHERE p.codigo_copidrogas='".$proveedor['nit']."' ";

        $dataTransferencista = $emcConect->query($sqlTransferencista);

        $datosTransferencista = array();
        $transferencista = array();
        foreach($dataTransferencista as $tran){
            $datosTransferencista[$tran['id']] = $tran['id'];
        }

        //$output->writeln("Se abre la conexion FTP para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
        
        //SE PONE EN COMENTARIO LA CONEXION FTP PARA HACER PRUEBAS
        $conexionFTP=@ftp_connect($this->container->getParameter('host_ftp'),$this->container->getParameter('port'));
        @ftp_login($conexionFTP,$this->container->getParameter('user_ftp'),$this->container->getParameter('pass_ftp'));
        
        //Validamos si el directorio existe

        if($path == "/"){
            //$output->writeln("4.2.2 El proveedor [".$proveedor['proveedor']."] no tiene carpeta asignada para procesar archivos.");
            //$this->registralog("4.2.2 El proveedor [".$proveedor['proveedor']."] no tiene carpeta asignada para procesar archivos.");
        }else{
        
        
            //SE PONE EN CONMENTARIO LA CONEXION FTP PARA HACER PRUEBAS
            if(!@ftp_chdir($conexionFTP,$path)){
               $raiz=explode('/',$path);
               if(!@ftp_mkdir($conexionFTP,$raiz[0])){
                   
                 //$this->registralog("4.2.1 Se crea la carpeta ".$raiz[0]." para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
                 
                 $noHayDirectorio=true;
               }else{
                   @ftp_chdir($conexionFTP,$raiz[0]);
                   @ftp_mkdir($conexionFTP,$raiz[1]);
                   @ftp_chdir($conexionFTP,$raiz[1]);
                   if(!@ftp_chdir($conexionFTP,'procesados')){
                        @ftp_mkdir($conexionFTP,'procesados');
                        
                        //$this->registralog("4.2.1 Se crea la carpeta procesados para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
                        
                    }else{
                        @ftp_chdir($conexionFTP,'../');
                    }

                    if(!@ftp_chdir($conexionFTP,'inventario')){
                        @ftp_mkdir($conexionFTP,'inventario');
                        
                        //$this->registralog("4.2.1 Se crea la carpeta inventario para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
                    }else{
                        @ftp_chdir($conexionFTP,'../');   
                    }

                    if(!@ftp_chdir($conexionFTP,'pendienteActualizar')){
                        @ftp_mkdir($conexionFTP,'pendienteActualizar');
                        
                        //$this->registralog("4.2.1 Se crea la carpeta pendienteActualizar para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
                    }else{
                        @ftp_chdir($conexionFTP,'../');   
                    }

                    if(!@ftp_chdir($conexionFTP,'confirmados')){
                        @ftp_mkdir($conexionFTP,'confirmados');
                        
                        //$this->registralog("4.2.1 Se crea la carpeta confirmados para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
                    }else{
                        @ftp_chdir($conexionFTP,'../');   
                    }
               }
              
            }else{
                $noHayDirectorio=true;

                if(!@ftp_chdir($conexionFTP,'pendienteActualizar')){
                    @ftp_mkdir($conexionFTP,'pendienteActualizar');
                }else{
                    @ftp_chdir($conexionFTP,'../');   
                }

                if(!@ftp_chdir($conexionFTP,'confirmados')){
                    @ftp_mkdir($conexionFTP,'confirmados');
                }else{
                    @ftp_chdir($conexionFTP,'../');   
                }

            }


            $clientesConsultados=$productosRegistrados=array();
            $iteradorProductos=0;

            if(isset($arrayProveedoresFtp[$proveedor['nit']])){
                //BANDERA PROVISIONAL PARA PRUEBA 
                //$noHayDirectorio=true;
                if($noHayDirectorio){
 
                    //$output->writeln("Se buscan archivos a procesar ");
                    //SE PONE EN COMENTARIO LA BUSQUEDA DE ARCHIVOS PARA HACER PRUEBAS
                    $documentos = ftp_nlist($conexionFTP, ".");
                    foreach ($documentos as $file) {
                        if(substr($file, -3)=='csv'||substr($file, -3)=='CSV'||
                           substr($file, -3)=='TXT'||substr($file, -3)=='txt' ){
                            $arrayArchivosAProcesar[]=$file;

                            //se descargan los archivos en una carpeta de respaldo
                            @ftp_get($conexionFTP,$dir."convenios/vogue/".$file,$file, FTP_BINARY);
                        }
                    }

                    //$arrayArchivosAProcesar[]="prueba_ftp.csv";

                    unset($documentos);
                    
                    $cont=0;
                    $i=1;
                    if(count($arrayArchivosAProcesar)>0){
                        $arrayFacturas=array();
                        foreach($arrayArchivosAProcesar as $archivo){

                            $arrayFactuarasArchivo = array();
                            $pedidosActualizarFacturas = array();
                           
                            //if($i <= 4){

                            
                                //$output->writeln("Se inicia el procesamiento del archivo ".$archivo);
                                //$this->registralog("Se inicia el procesamiento del archivo ".$archivo." tarea Convenios");

                                $drogueriasArchivo = array();
                                $drogueriasRemision = array();
                                $pedidosActualizar = array();
                                //SE COMENTAREA LA DESCARGA DEL ARCHIVO DEL FTP MIENTRAS PRUEBAS
                                @ftp_get($conexionFTP,$dir.$archivo,$archivo, FTP_BINARY);
                                @ftp_delete($conexionFTP,$archivo);
                                if($fp = @fopen($dir.$archivo, "rb")){//Abrir el archivo en modo escritura.
                                    $logRespuesta=array();
                                    if (count(fgetcsv($fp, 1000, ";")) > 2) {//leer el archivo en busca de campos CSV.

                                        if(filesize($dir.$archivo) >0){

                                            fseek($fp, 0);
                                            $informe[$cantArchivos]['archivo']['name']=$archivo;
                                            $informe[$cantArchivos]['archivo']['size']= filesize($dir.$archivo) . ' bytes.';
                                            //Se leen las filas del archivo.

                                            
                                            while($datos = fgetcsv($fp, 1000, ";")){
                                                
                                                if(isset($datos[0]) && isset($datos[3]) && isset($datos[1]) && isset($datos[2]) && isset($datos[5])){
                                                    
                                                    if(isset($datosTransferencista[trim($datos[5])] ) ){

                                                        //datos[0] = codigo drogueria
                                                        //datos[1] = codigo producto
                                                        //datos[2] = cantidad
                                                        //datos[3] = codigo barras
                                                        //datos[4] = centro
                                                        //datos[5] = transferencista
                                                        //datos[6] = remision


                                                        $datos[0] = trim($datos[0]);
                                                        //$datos[1] = trim($datos[1]);
                                                        $datos[1] = trim(str_pad((string)$datos[1], 9, "0", STR_PAD_LEFT));
                                                        $datos[2] = trim($datos[2]);
                                                        $datos[3] = trim($datos[3]);
                                                        $datos[4] = trim($datos[4]);
                                                        $datos[5] = trim($datos[5]);
                                                        $datos[6] = (isset($datos[6]))?trim($datos[6]):'';

                                                        $transferencista['id'] = $datosTransferencista[trim($datos[5])];
                                                   
                                                        /*$datos[6] = ( isset($datos[6]))? trim($datos[6]):'';//Numero de cajas
                                                        $datos[7] = ( isset($datos[7]))? trim($datos[7]):'';//Numero de Factura
                                                        $datos[8] = ( isset($datos[8]))? trim($datos[8]):'';//Remision
                                                        
                                                        $datos[9] = ( isset($datos[9]))? trim($datos[9]):'';//Consecutivo pedido
                                                        */


                                                            if( ($datos[0]!='') && ( is_numeric($datos[2]) && ( strlen($datos[2])<=3 && $datos[0]>=1 ))){//validar cÃ³digo de la DroguerÃ­a y cantidad solicitada.



                                                                if($datos[2]>=1){
                                                                    //si no tiene consecutivo es producto para pedido nuevo
                                                                    $drogueriasArchivo[$datos[0]][$datos[3]] = array('cantidad' => $datos[2], 'producto' => $datos[3], 'codigo' => $datos[1]);
                                                                    if(isset($datos[6]) && $datos[6]!=''){
                                                                        $drogueriasRemision[$datos[0]] = $datos[6];
                                                                    }
                                                                }



                                                               
                                                            }else{
                                                                //$output->writeln("Buscando cliente: ".$datos[0]);
                                                                //$informe[$cantArchivos]['noLeidas'][]='Droguería o cantidad invalida. Línea: '.$cont.'.';
                                                            }

                                                    }else{
                                                        $informe[$cantArchivos]['error'] = 'Error. Transferencista no encontrado['.$archivo.'].';
                                                        $logRespuesta[]= "Transferencista ".$datos[5]." no encontrado para el pedido de la drogueria ".$datos[0].".";
                                                    }
                                                    
                                           
                                                }else{
                                                    $informe[$cantArchivos]['error'] = 'Error. Estructura del archivo ['.$archivo.'] incorrecta.';
                                                    $logRespuesta[]= "Estructura del archivo incorrecta.";
                                                }//fin if
                                            }//fin where
                                            unset($productosIngresados);

                                           // dump($drogueriasArchivo);
                                            //se insertan los productos.
                                            foreach($drogueriasArchivo as $codigo => $value){

                                                //$output->writeln("Buscando cliente: ".$datos[0]);
                                                $cliente=$ems->createQueryBuilder('c')->select('c.id,c.cupoAsociado,c.tipoCliente,c.codigo,c.centro,c.ciudad,c.depto,c.drogueria,c.email')
                                                            ->from('FTPAdministradorBundle:Cliente','c')
                                                            ->where('c.codigo=?1')
                                                            ->setMaxResults(1)
                                                            ->setParameter(1,$codigo)
                                                            ->getQuery()->getOneOrNullResult();

                                                if($cliente){
                                                    //$output->writeln("Cliente encontrado : ".$datos[0]);
                                                   
                                                    $clientesConsultados[$codigo]['id']=$cliente['id'];
                                                    $clientesConsultados[$codigo]['cupoAsociado']=$cliente['cupoAsociado'];
                                                    $clientesConsultados[$codigo]['tipoCliente']=$cliente['tipoCliente'];
                                                    $clientesConsultados[$codigo]['codigo']=$cliente['codigo'];
                                                    $clientesConsultados[$codigo]['centro']=$cliente['centro'];
                                                    $clientesConsultados[$codigo]['ciudad']=$cliente['ciudad'];
                                                    $clientesConsultados[$codigo]['depto']=$cliente['depto'];
                                                    $clientesConsultados[$codigo]['drogueria']=$cliente['drogueria'];
                                                    $clientesConsultados[$codigo]['email']=$cliente['email'];


                                                    /**************SE CONSULTA EL WEB SERVICE************/
                                                    /*$cupo = $tarea->retornoEstadoCupo($cliente['codigo']);
                                                    if($cupo['estadoCupo'] != 'Activo'){
                                                        echo " la drogueria ".$cliente['codigo']." esta bloqueada por ".$cupo['estadoCupo'];
                                                    }else{
                                                        
                                                    }*/
                                                    /***************************************************/

                                                    $eliminar = $emc->createQuery(" DELETE FROM FTPAdministradorBundle:PedidoDescripcion dp WHERE dp.productoEstado =:estado AND dp.transferencista=:idTransferencista AND dp.drogueriaId=:dorgueria")
                                                    ->setParameter('estado', 10)
                                                    ->setParameter('idTransferencista',$transferencista['id'])
                                                    ->setParameter('dorgueria',$codigo)
                                                    ->getResult();

                                                    //se insertan productos.
                                                    foreach($value as $codProducto => $producto){

                                                        //$output->writeln("Buscando en el inventario: ".$codProducto);


                                                        $inventario = $emc->createQueryBuilder('ip')->select('ip')
                                                            ->from('FTPAdministradorBundle:InventarioProducto','ip')
                                                            ->innerJoin('ip.proveedor','pr')
                                                            ->where('ip.codigoBarras=?1')
                                                            ->andWhere('ip.codigo=?2')
                                                            ->andWhere('pr.codigoCopidrogas=?3')
                                                            ->setMaxResults(1)
                                                            ->setParameter(1,$codProducto)
                                                            ->setParameter(2,$producto['codigo'])
                                                            ->setParameter(3,$proveedor['nit'])
                                                            ->setMaxResults(1)
                                                            ->getQuery()->getResult();

                                                        $contadorProductos=0;
                                                        if($inventario){

                                                            //Array que se lee cuando se envia correo de creación de pedido.
                                                            $productosRegistrados[$codigo][$iteradorProductos]['codigo']=  $inventario[0]->getCodigo();
                                                            $productosRegistrados[$codigo][$iteradorProductos]['codigoBarras']=  $inventario[0]->getCodigoBarras();
                                                            $productosRegistrados[$codigo][$iteradorProductos]['descripcion']=  $inventario[0]->getDescripcion();
                                                            $productosRegistrados[$codigo][$iteradorProductos]['presentacion']=  $inventario[0]->getPresentacion();
                                                            $productosRegistrados[$codigo][$iteradorProductos]['precio']=  $inventario[0]->getPrecio();
                                                            $productosRegistrados[$codigo][$iteradorProductos]['iva']=  $inventario[0]->getIva();
                                                            $productosRegistrados[$codigo][$iteradorProductos]['descuento']=  $inventario[0]->getDescuento();
                                                            $productosRegistrados[$codigo][$iteradorProductos]['cantidad']=  $producto['cantidad'];
                                                            $iteradorProductos++;

                                                            //$this->registralog("se inserta el producto ".$producto['producto']." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."] archivo[".$archivo."]");                                                      
                                                            //$output->writeln("se inserta el producto ".$producto['producto']." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."]archivo[".$archivo."]");
                                                            
                                                            $pedidoDescripcion=new PedidoDescripcion();

                                                            $pedidoDescripcion = $this->crearPedidosDescripcion($pedidoDescripcion, $inventario[0], $cliente['id'], $producto['cantidad'],$emc,$codigo ,$transferencista['id']);
                                                            $emc->persist($pedidoDescripcion);
                                                            $emc->flush();

                                                            $contadorProductos++;
                                                            //$output->writeln("Insertado: ".$contadorProductos);

                                                            if($pedidoDescripcion->getId()){
                                                                
                                                                $informe[$cantArchivos][$codigo]['insertados'][$producto['codigo']]=$producto['codigo'];
                                                            }else{
                                                                $informe[$cantArchivos][$codigo]['noIsertados'][]=$producto['codigo'];
                                                            } 



                                                        }else{
                                                            //$output->writeln(" NO se inserta el producto ".$codProducto." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."] ");
                                                            //$this->registralog("Producto NO encontrado ".$codProducto." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."]");

                                                            $logRespuesta[]= "Producto NO encontrado ".$codProducto." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."]";
                                                        }

                                                    }
                                                }else{
                                                     //$output->writeln("Cliente no encontrado : ".$datos[0]); 
                                                     $logRespuesta[]= "Cliente no encontrado : ".$datos[0];
                                                }

                                            }//fin foreac


                                            //se crea el pedido.
                                            foreach($drogueriasArchivo as $codigoDrogueria => $value){

                                                $minimoPedido = $emc->getRepository('FTPAdministradorBundle:VariablesSistema')->find(1);
                                                if($minimoPedido){$minimo=$minimoPedido->getMinimoPedido();}else{$minimo=0;}
                                                //$minimo=0;

                                                $totalPedido=$utilidades->totalPedido($codigoDrogueria,0,0,$emc,$proveedor['nit']);

                                                if($totalPedido['total'] >= $minimo && $totalPedido['total'] > 0 ){
                                                    
                                                    //$output->writeln("se procede a la confirmacion del pedido de la drogueria ".$codigoDrogueria);
                                                    //$this->registralog("se procede a la confirmacion del pedido de la drogueria ".$codigoDrogueria);

                                                     $contador=$emc->createQueryBuilder()
                                                    ->select('SUM(pd.cantidadPedida) AS cantidadUni,count(pd.id) AS cantidad')
                                                    ->from('FTPAdministradorBundle:InventarioProducto','ip')
                                                    ->join('FTPAdministradorBundle:PedidoDescripcion','pd','WITH','pd.pdtoId = ip.id')
                                                    ->join('FTPAdministradorBundle:Proveedor','p','WITH','ip.proveedor = p.id')
                                                    ->where(' pd.drogueriaId =?1 AND pd.productoEstado=10 AND p.codigoCopidrogas=?2 AND pd.transferencista=?3')
                                                    ->setParameter(1,$codigoDrogueria)
                                                    ->setParameter(2,$proveedor['nit'])
                                                    ->setParameter(3,$transferencista['id'])
                                                    ->getQuery()->getOneOrNullResult();

                                                    //dump($contador);
                                                    
                                                    $date = new \DateTime('now');
                                                    $pedido = new Pedido();
                                                     
                                                    $pedido->setConsecutivo('1');
                                                    $pedido->setRemision('0');
                                                    $pedido->setFecha(new \DateTime());
                                                    $pedido->setFechaPedido(new \DateTime());
                                                    $pedido->setCodigoDrogueria($codigoDrogueria);
                                                    $pedido->setTransferencista($emc->getReference('FTPAdministradorBundle:Transferencista',$transferencista['id']));
                                                    $pedido->setProveedor($emc->getReference('FTPAdministradorBundle:Proveedor',$arrayProveedoresFtp[$proveedor['nit']]));
                                                    $pedido->setEstado(0);
                                                    $pedido->setTotalPesos($totalPedido['total']);
                                                    $pedido->setProductos($contador['cantidad']);
                                                    $pedido->setNumeroCajas(0);
                                                    $pedido->setFechaEnviadoProveedor($date);
                                                    $pedido->setFechaRecibidoCopidrogas($date);
                                                    $pedido->setFechaEnviadoAsociado($date);
                                                    $pedido->setNumeroFactura('');
                                                    $pedido->setFechaEliminado($date);
                                                    $pedido->setCargadoFtp(1);
                                                    $pedido->setNumeroProductos($contador['cantidadUni']);   
                                                    if(isset($drogueriasRemision[$codigoDrogueria])){
                                                        $pedido->setRemision($drogueriasRemision[$codigoDrogueria]);
                                                    }

                                                    $emc->persist($pedido);
                                                    $emc->flush();
                                                    $pedido->setConsecutivo($pedido->getId().'-FTP-'.$codigoDrogueria);



                                                    $emc->persist($pedido);
                                                    $emc->flush();
                                                                                                    
                                                    $pedidosGenerados[] = $pedido->getId();

                                                    $logRespuesta[]= "Pedido: " .$pedido->getConsecutivo(). " NumProductos:" . $contador['cantidadUni'] . " Drogueria:" . $codigoDrogueria;

                                                    //$output->writeln( "Pedido: " .$pedido->getConsecutivo(). " NumProductos:" . $contador['cantidadUni'] . " Drogueria:" . $codigoDrogueria);

                                                    $pedidosTotales++;

                                                    $informe[$cantArchivos][$codigoDrogueria]['drogueria']=$codigoDrogueria;
                                                    $informe[$cantArchivos][$codigoDrogueria]['cantUnit']=$contador['cantidadUni'];
                                                    $informe[$cantArchivos][$codigoDrogueria]['canTotal']=$contador['cantidad'];
                                                    $informe[$cantArchivos][$codigoDrogueria]['totalPedido']=$totalPedido['total'];
                                                    $informe[$cantArchivos][$codigoDrogueria]['pedido']=$pedido->getConsecutivo();
                                                    
                                                    //$output->writeln("pedido generado con el consecutivo ".$pedido->getConsecutivo()." para la drogueria ".$codigoDrogueria);
                                                    
                                                    
                                                    //$this->registralog('4.2.7 Se genera el pedido con el consecutivo ['.$pedido->getConsecutivo().']. para la drogueria '.$codigoDrogueria.' con el proveedor '.$proveedor['proveedor']);
                                                    
                                                    if($pedido->getId()){
                                                     

                                                        //$output->writeln("se actualiza el detalle del pedido ".$pedido->getConsecutivo()." para la drogueria ".$codigoDrogueria);

                                                        $emc->createQueryBuilder()->update('FTPAdministradorBundle:PedidoDescripcion','pd')
                                                        ->set('pd.productoEstado','?1')
                                                        ->set('pd.pedido','?2')
                                                        ->where('pd.productoEstado=10  AND pd.drogueriaId=?3 AND pd.transferencista=?4')
                                                        ->setParameter(1,1)
                                                        ->setParameter(2,$pedido->getId())
                                                        ->setParameter(3,$codigoDrogueria)
                                                        ->setParameter(4,$transferencista['id'])
                                                        ->getQuery()->execute();



                                                        if( isset( $clientesConsultados[$codigoDrogueria]['cupoAsociado'], $clientesConsultados[$codigoDrogueria]['drogueria'] ) ){


                                                        }else{
                                                            //$output->writeln('Se creo el pedido : '.$pedido->getConsecutivo().' Pero no fue posible enviar el correo de confirmacion. Info del Asociado incompleta.');
                                                            //$this->registralog('Se creo el pedido : '.$pedido->getConsecutivo().' Pero no fue posible enviar el correo de confirmacion. Info del Asociado incompleta.');

                                                        }

                                                    }else{
                                                        $informe[$cantArchivos][$codigoDrogueria]=$this->descartarDetallePedido($codigoDrogueria,$transferencista,$emc,$informe[$cantArchivos][$drogueria['codigo']]);
                                                        //$output->writeln("se descarta el  pedido ".$pedidosTotales." para la drogueria ".$codigoDrogueria);

                                                        //$this->registralog("se descarta el  pedido ".$pedidosTotales." para la drogueria ".$codigoDrogueria);


                                                    }

                                                }else{

                                                    //$output->writeln("no cumple el  pedido  ".$pedidosTotales." para la drogueria ".$codigoDrogueria);

                                                    // si el total del pedido no alcanza el mínimo permitido los items son descargados
                                                    //para la iteración en curso.
                                                    if(isset($informe[$cantArchivos][$codigoDrogueria])){
                                                        //$output->writeln("se elimina el pedido de la drotgueria ".$codigoDrogueria." por no cumplir con el minimo ");
                                                    
                                                        //$this->registralog('4.2.6 Error. Se elimina la informacion del pedido de la drogueria '.$codigoDrogueria.' por no cumplir con el valor minimo del pedido.');
                                                        
                                                        $informe[$cantArchivos][$codigoDrogueria]=$this->descartarDetallePedido($codigoDrogueria,$transferencista['id'],$emc,$informe[$cantArchivos][$codigoDrogueria]);
                                                        $arrayEstado[$drogueria['codigo']]['noAceptado']= 1;
                                                        $informe[$cantArchivos][$drogueria['codigo']]['totalPedido']=$totalPedido['total'];
                                                    }
                                                }

                                            }//fin foreach




                                            unset($pedidoNocreado);
                                            $informe[$cantArchivos]['pedidosTotales']=$pedidosTotales;

                                            //Registrar información de último pedido cargado, cantidad y última transacción con Convenios.
                                            $this->updateProveedor($proveedor['nit'],$pedidosTotales, 1);

                                            //Registrar actividad en logsAdministraodor
                                            //$this->registralog('4.2.8 Cargar '.$pedidosTotales.' pedido(s) para el proveedor '.$proveedor['proveedor'].' ['.$proveedor['nit'].'] ');
                                            
                                            //SE COMENTAREA LA CREACION DEL ARCHIVO DE LOG MIENTRAS PRUEBAS
                                            $txt[$cantArchivos]=$tarea2->generarLog($informe[$cantArchivos],$path, $this->get('kernel')->getRootDir().'/../web/', $logRespuesta, $archivo);
                                            $cantArchivos++;

                                        }else{

                                            $informe['error'] = 'Estructura del archivo incorrecta. Archivo['.$archivo.'] vacio.';

                                            $logRespuesta[]= "Estructura del archivo incorrecta. Archivo [".$archivo."] vacio";

                                            $txt[$cantArchivos]=$tarea2->generarLog($informe,$path, $this->get('kernel')->getRootDir().'/../web/', $logRespuesta, $archivo);
                                            $cantArchivos++;
                                        }


                                        

                                    }else{
                                        $informe['error'] = 'Por favor verifique la estructura del archivo['.$archivo.'].No fue posible iniciar la operación.';

                                        $logRespuesta[]= "Estructura del archivo incorrecta.";

                                        $txt[$cantArchivos]=$tarea2->generarLog($informe,$path, $this->get('kernel')->getRootDir().'/../web/', $logRespuesta, $archivo);
                                        $cantArchivos++;
                                    }

                                    fclose($fp);
                                    
                                }else{
                                    $informe[$cantArchivos]['error'] = 'Error. Verifique la ruta y el nombre del archivo['.$archivo.'].';
                                    //$this->registralog('4.2.3 Error. Verifique la ruta y el nombre del archivo['.$archivo.'].');
                                    //$output->writeln('Error. Verifique la ruta y el nombre del archivo['.$archivo.'].');
                                }
                                //SE COMENTAREA LA MOVIDA DEL ARCHIVO PROCESADO MIENTRAS PRUEBAS
                                //mover el archivo a la carpeta procesados    
                                $tarea2->transaccionFTP($dir.$archivo,$archivo,$path.'procesados/');

                                /*if($fp){
                                    fclose($fp);
                                }*/
                                
                                @unlink($dir.$archivo);
                                //@ftp_delete($conexionFTP,$archivo);
                                
                            //}//fin fi
                            $i++;
                            
                            $cont++;
                            unset($drogueriasArchivo);
                            unset($arrayFactuarasArchivo);
                            
                            unset($pedidosActualizar);
                            unset($pedidosActualizarFacturas);

                        }//fin foreach



                    }else{

                        //$this->enviarCorreo(0, 0, 0, 0, 0, 0 );
                        $sinArchivos[]='El proveedor  '.$proveedor['proveedor'].' .No tiene archivos para procesar a Convenios. ';
                        //$this->registralog("4.2.2 El proveedor [".$proveedor['proveedor']."] no tiene archivos para procesar a Convenios.");
                    }
                      
                }
                
                // se actualizan pedidos pendientes y se crean archivos para actualizar 
                //$this->conveniosPedidosPendientesActualizar($pedidosGenerados, $path, $arrayProveedoresFtp[$proveedor['nit']],$output, $dataClientes);
            }
        } 
        //SE COMENTAREA EL CIERRE DE LA CONEXION FTP    
        //ftp_close($conexionFTP);
        return array('informe' =>$informe ,'log'=>$txt,'sinArchivos'=>$sinArchivos);
    }


    public function procesarPedidoMultipleAnteriorAction($proveedor,$path,$session) {
       
        //conxiones.
        $em=$this->getDoctrine()->getManager();
        $emc=$this->getDoctrine()->getManager('convenios');
        $ems = $this->getDoctrine()->getManager('sipasociados');
        $dir = $this->get('kernel')->getRootDir().'/../web/';
                
        $proveedoresFtp = $emc->getRepository('FTPAdministradorBundle:Proveedor')->findAll();
        $arrayProveedoresFtp = array();
        foreach($proveedoresFtp as $datosProveedor){
            $arrayProveedoresFtp[$datosProveedor->getCodigoCopidrogas()] = $datosProveedor->getId();
        }

        //variables de entorno.
        $drogueriasArray=array();
        $cont=0;
        $pedidosTotales=0;
        $informe=array();
        $productosIngresados=array();
        $insertar=true;
        $totalPedido='';
        $directorio = false;
        $arrayArchivosAProcesar=array();
        $txt=array();
        $sinArchivos=array();
        $noHayDirectorio=true;
        $cantArchivos = 0;
        $pedidosGenerados=array();
        $logRespuesta=array();
        $productosRegistrados=array();//array que guarda la informacion de los productos insertados para enviarlos por correo cuando se confirme el pedido.
        $iteradorProductos=0;
        $clientesConsultados=array();
        $aceptacionesContrato= array();

        //servicios
        $tarea=$this->container->get('utilidadesAdministrador');
        $tarea2=$this->container->get('generarArchivos');

        $utilidades=$this->get('utilidadesAdministrador');
          
        $transferencista=$emc->createQueryBuilder()
        ->select('t.id,t.nombres AS transfNombres,p.nombre AS provNombre')
        ->from('FTPAdministradorBundle:Proveedor','p')
        ->join('FTPAdministradorBundle:Transferencista','t','WITH','p.id=t.proveedor')
        ->where('p.codigoCopidrogas=?1')
        ->setParameter(1,$proveedor['nit'])
        ->setMaxResults(1)
        ->getQuery()->getOneOrNullResult();

    
        $conexionFTP=@ftp_connect($this->container->getParameter('host_ftp'),$this->container->getParameter('port'));
        @ftp_login($conexionFTP,$this->container->getParameter('user_ftp'),$this->container->getParameter('pass_ftp'));


        //Validamos si el directorio existe
        if(!@ftp_chdir($conexionFTP,$path)){
           $raiz=explode('/',$path);
            @ftp_mkdir($conexionFTP,$raiz[0]);
            @ftp_chdir($conexionFTP,$raiz[0]);
            @ftp_mkdir($conexionFTP,$raiz[1]);
            @ftp_chdir($conexionFTP,$raiz[1]);
            if(!@ftp_chdir($conexionFTP,'procesados')){
                @ftp_mkdir($conexionFTP,'procesados');
            }else{
                @ftp_chdir($conexionFTP,'../');
            }

            if(!@ftp_chdir($conexionFTP,'inventario')){
                @ftp_mkdir($conexionFTP,'inventario');
            }else{
                @ftp_chdir($conexionFTP,'../');   
            }
            if(!@ftp_chdir($conexionFTP,'pendienteActualizar')){
                @ftp_mkdir($conexionFTP,'pendienteActualizar');
            }else{
                @ftp_chdir($conexionFTP,'../');   
            }
            if(!@ftp_chdir($conexionFTP,'confirmados')){
                @ftp_mkdir($conexionFTP,'confirmados');
            }else{
                @ftp_chdir($conexionFTP,'../');   
            }
  
          
        }   

        if($noHayDirectorio){

            $documentos = ftp_nlist($conexionFTP, ".");
            foreach ($documentos as $file) {
                if(substr($file, -3)=='csv'||substr($file, -3)=='CSV'||
                   substr($file, -3)=='TXT'||substr($file, -3)=='txt' ){
                    $arrayArchivosAProcesar[]=$file;
                }
            }
            unset($documentos);
            
            if($arrayArchivosAProcesar){
           
                foreach($arrayArchivosAProcesar as $archivo){
                    ftp_get($conexionFTP,$dir.$archivo,$archivo, FTP_BINARY);
                
                    

                    if($fp = @fopen($dir.$archivo, "rb")){//Abrir el archivo en modo escritura.
                        
                        if (count(fgetcsv($fp, 1000, ";")) > 2) {//leer el archivo en busca de campos CSV.
                            fseek($fp, 0);
                            $informe[$cantArchivos]['archivo']['name']=$archivo;
                            $informe[$cantArchivos]['archivo']['size']= filesize($dir.$archivo) . ' bytes.';
                            while($datos = fgetcsv($fp, 1000, ";")){
                                
                              if( isset($datos[0])  && isset($datos[2])  && isset($datos[3]) ){

                                    
                                
                                $datos[0] = trim($datos[0]);
                                $datos[1] = trim($datos[1]);
                                $datos[2] = trim($datos[2]);

                                    if( ($datos[0]!='') && ( is_numeric($datos[2]) && ( strlen($datos[2])<=3 && $datos[0]>=1 ))){//validar cÃ³digo de la DroguerÃ­a y cantidad solicitada.
                                        //si el array está vacio o el puntero no existe en el array. Consulta nueva droguería.
                                        if(empty($clientesConsultados) || !isset($clientesConsultados[$datos[0]])  ){

                                            $cliente=$ems->createQueryBuilder('c')->select('c.id,c.cupoAsociado,c.tipoCliente,c.codigo,c.centro,c.ciudad,c.depto,c.drogueria,c.email')
                                            ->from('FTPAdministradorBundle:Cliente','c')
                                            ->where('c.codigo=?1')
                                            ->setMaxResults(1)
                                            ->setParameter(1,$datos[0])
                                            ->getQuery()->getOneOrNullResult();

                                            $clientesConsultados[$datos[0]]['id']=$cliente['id'];
                                            $clientesConsultados[$datos[0]]['cupoAsociado']=$cliente['cupoAsociado'];
                                            $clientesConsultados[$datos[0]]['tipoCliente']=$cliente['tipoCliente'];
                                            $clientesConsultados[$datos[0]]['codigo']=$cliente['codigo'];
                                            $clientesConsultados[$datos[0]]['centro']=$cliente['centro'];
                                            $clientesConsultados[$datos[0]]['ciudad']=$cliente['ciudad'];
                                            $clientesConsultados[$datos[0]]['depto']=$cliente['depto'];
                                            $clientesConsultados[$datos[0]]['drogueria']=$cliente['drogueria'];
                                            $clientesConsultados[$datos[0]]['email']=$cliente['email'];

                                        }
                                            


                                        if(isset($clientesConsultados[$datos[0]])){
                                            
                                            if(empty($aceptacionesContrato) || !isset($aceptacionesContrato[$datos[0]]) ){
                                                
                                                $aceptacionContrato =$ems->createQueryBuilder('ac')->select('ac.pruebas')
                                                ->from('FTPAdministradorBundle:AceptacionContrato','ac')
                                                ->where('ac.codigoDrogeria=?1')
                                                ->setParameter(1,$datos[0])
                                                ->getQuery()->getOneOrNullResult();

                                                $aceptacionesContrato[$datos[0]]=$aceptacionContrato['pruebas'];

                                            }

                                            

                                            if($aceptacionesContrato[$datos[0]] != 1){

                                                $drogueriasArray[$datos[0]][0]=1;
                                                $drogueriasArray[$datos[0]][1]=$clientesConsultados[$datos[0]]['id'];
                                                $drogueriasArray[$datos[0]]['drogId']=$clientesConsultados[$datos[0]]['id'];
                                                $drogueriasArray[$datos[0]]['cupo']=$clientesConsultados[$datos[0]]['cupoAsociado'];
                                                $drogueriasArray[$datos[0]]['tipoCliente']=$clientesConsultados[$datos[0]]['tipoCliente'];
                                                $drogueriasArray[$datos[0]]['codigo']=$clientesConsultados[$datos[0]]['codigo'];
                                                $drogueriasArray[$datos[0]]['centro']=$clientesConsultados[$datos[0]]['centro'];
                                                $drogueriasArray[$datos[0]]['ciudad']=$clientesConsultados[$datos[0]]['ciudad'];
                                                $drogueriasArray[$datos[0]]['departamento']=$clientesConsultados[$datos[0]]['depto'];
                                                $drogueriasArray[$datos[0]]['drogueria']=$clientesConsultados[$datos[0]]['drogueria'];
                                                $drogueriasArray[$datos[0]]['email']=$clientesConsultados[$datos[0]]['email'];

                                            }else{

                                                $drogueriasArray[$datos[0]][0]=2;
                                                $informe[$cantArchivos]['pruebas'][]=$datos[0];
                                                continue;
                                            }
                                            
                                        }else{

                                            $informe[$cantArchivos]['noEncontradas'][]=$datos[0];
                                            $drogueriasArray[$datos[0]][0]=0;

                                            $logRespuesta[]= "Codigo de drogueria No Encontrada: " . $datos[0];
                                            continue;
                                        }


                                        $pedidoDescripcion = $emc->createQueryBuilder('pd')->select('pd')
                                            ->from('FTPAdministradorBundle:PedidoDescripcion','pd')
                                            ->where('pd.productoCodigoBarras=?1 AND pd.productoEstado=0 AND pd.drogueriaId=?2')
                                            ->setMaxResults(1)
                                            ->setParameter(1,$datos[3])
                                            ->setParameter(2,$drogueriasArray[$datos[0]]['codigo'])
                                            ->getQuery()->getOneOrNullResult();

                                        if(!$pedidoDescripcion){                       
                                            $pedidoDescripcion = new PedidoDescripcion();
                                        }

                                        $inventario = $emc->createQueryBuilder('ip')->select('ip')
                                            ->from('FTPAdministradorBundle:InventarioProducto','ip')
                                            ->where('ip.codigoBarras=?1')
                                            ->setMaxResults(1)
                                            ->setParameter(1,$datos[3])
                                            ->setMaxResults(1)
                                            ->getQuery()->getResult();

                                        if ($inventario) {

                                            if($transferencista){

                                                if(isset($productosIngresados[$datos[0]])){
                                                    $insertar=(in_array($inventario[0]->getCodigoBarras(),$productosIngresados[$datos[0]]))?false:true;  
                                                }
                                                // si el producto no ha sido insertado o si la droguerÃ­a no ha sido procesada.
                                                if($insertar ||!(isset($productosIngresados[$datos[0]]))){

                                                    //Array que se lee cuando se envia correo de creación de pedido.
                                                    $productosRegistrados[$datos[0]][$iteradorProductos]['codigo']=  $inventario[0]->getCodigo();
                                                    $productosRegistrados[$datos[0]][$iteradorProductos]['codigoBarras']=  $inventario[0]->getCodigoBarras();
                                                    $productosRegistrados[$datos[0]][$iteradorProductos]['descripcion']=  $inventario[0]->getDescripcion();
                                                    $productosRegistrados[$datos[0]][$iteradorProductos]['presentacion']=  $inventario[0]->getPresentacion();
                                                    $productosRegistrados[$datos[0]][$iteradorProductos]['precio']=  $inventario[0]->getPrecio();
                                                    $productosRegistrados[$datos[0]][$iteradorProductos]['iva']=  $inventario[0]->getIva();
                                                    $productosRegistrados[$datos[0]][$iteradorProductos]['descuento']=  $inventario[0]->getDescuento();
                                                    $productosRegistrados[$datos[0]][$iteradorProductos]['cantidad']=  $datos[2];
                                                    $iteradorProductos++;

                                                    $pedidoDescripcion = $this->crearPedidosDescripcion($pedidoDescripcion, $inventario[0], $cliente['id'], $datos[2],$emc,$cliente['codigo'],$transferencista['id']);
                                                    $emc->persist($pedidoDescripcion);
                                                    $emc->flush();
                                                    if($pedidoDescripcion->getId()){
                                                        $productosIngresados[$datos[0]][]=$inventario[0]->getCodigoBarras();
                                                        $informe[$cantArchivos][$datos[0]]['insertados'][$datos[3]]=$datos[3];
                                                    }else{
                                                        $informe[$cantArchivos][$datos[0]]['noIsertados'][]=$datos[3];
                                                    }       
                                                }  
                                            }
                                        } else {
                                            $informe[$cantArchivos][$datos[0]]['noIsertados'][]='Producto no encontrado en el inventario '.$datos[3].'.';

                                            $logRespuesta[]= "Producto No Encontrado, Codigo: " . $datos[3];
                                        }
                                    }else{
                                            $informe[$cantArchivos]['noLeidas'][]='DroguerÃ­a o cantidad invalida. LÃ­nea: '.$cont.'.';
                                    }
                                
                               }else{
                                
                               }

                            }//fin where
                            unset($productosIngresados);
                            
                            $minimoPedido = $emc->getRepository('FTPAdministradorBundle:VariablesSistema')->find(1);
                            if($minimoPedido){$minimo=$minimoPedido->getMinimoPedido();}else{$minimo=0;}
                            foreach ($drogueriasArray as $drogueria) {
                                if($drogueria[0]==1){
                                    $totalPedido=$utilidades->totalPedido($drogueria['codigo'],0,0,$emc,$proveedor['nit']);

                                    if($totalPedido['total'] >= $minimo ){

                                         $contador=$emc->createQueryBuilder()
                                        ->select('SUM(pd.cantidadPedida) AS cantidadUni,count(pd.id) AS cantidad')
                                        ->from('FTPAdministradorBundle:InventarioProducto','ip')
                                        ->join('FTPAdministradorBundle:PedidoDescripcion','pd','WITH','pd.pdtoId = ip.id')
                                        ->join('FTPAdministradorBundle:Proveedor','p','WITH','ip.proveedor = p.id')
                                        ->where(' pd.drogueriaId =?1 AND pd.productoEstado=0 AND p.codigoCopidrogas=?2 AND pd.transferencista=?3')
                                        ->setParameter(1,$drogueria['codigo'])
                                        ->setParameter(2,$proveedor['nit'])
                                        ->setParameter(3,$transferencista['id'])
                                        ->getQuery()->getOneOrNullResult();
                                        
                                        $date = new \DateTime('now');
                                        $pedido = new Pedido();
                                         
                                        $pedido->setConsecutivo('1');
                                        $pedido->setRemision('0');
                                        $pedido->setFecha(new \DateTime());
                                        $pedido->setFechaPedido(new \DateTime());
                                        $pedido->setCodigoDrogueria($drogueria['codigo']);
                                        $pedido->setTransferencista($emc->getReference('FTPAdministradorBundle:Transferencista',$transferencista['id']));
                                        $pedido->setProveedor($emc->getReference('FTPAdministradorBundle:Proveedor',$arrayProveedoresFtp[$proveedor['nit']]));
                                        $pedido->setEstado(0);
                                        $pedido->setTotalPesos($totalPedido['total']);
                                        $pedido->setProductos($contador['cantidad']);
                                        $pedido->setNumeroCajas(0);
                                        $pedido->setFechaEnviadoProveedor($date);
                                        $pedido->setFechaRecibidoCopidrogas($date);
                                        $pedido->setFechaEnviadoAsociado($date);
                                        $pedido->setNumeroFactura('');
                                        $pedido->setFechaEliminado($date);
                                        $pedido->setCargadoFtp(1);
                                        $pedido->setNumeroProductos($contador['cantidadUni']);    
                                        //$pedido->setPedidoProcesado(1);
                                        //$pedido->setFechaProcesado($date);

                                        $emc->persist($pedido);
                                        $emc->flush();
                                        $pedido->setConsecutivo($pedido->getId().'-FTP-'.$drogueria['codigo']);
                                        $emc->persist($pedido);
                                        $emc->flush();

                                        $pedidosGenerados[] = $pedido->getId();

                                        $logRespuesta[]= "Pedido: " .$pedido->getConsecutivo(). " NumProductos:" . $contador['cantidadUni'] . " Drogueria:" . $drogueria['codigo'];

                                        $pedidosTotales++;

                                        $informe[$cantArchivos][$drogueria['codigo']]['drogueria']=$drogueria['drogueria'];
                                        $informe[$cantArchivos][$drogueria['codigo']]['cantUnit']=$contador['cantidadUni'];
                                        $informe[$cantArchivos][$drogueria['codigo']]['canTotal']=$contador['cantidad'];
                                        $informe[$cantArchivos][$drogueria['codigo']]['totalPedido']=$totalPedido['total'];

                                        $informe[$cantArchivos][$drogueria['codigo']]['pedido']=$pedido->getConsecutivo();
                                        
                                        if($pedido->getId()){

                                            $emc->createQueryBuilder()->update('FTPAdministradorBundle:PedidoDescripcion','pd')
                                            ->set('pd.productoEstado','?1')
                                            ->set('pd.pedido','?2')
                                            ->where('pd.productoEstado=0  AND pd.drogueriaId=?3 AND pd.transferencista=?4')
                                            ->setParameter(1,1)
                                            ->setParameter(2,$pedido->getId())
                                            ->setParameter(3,$drogueria['codigo'])
                                            ->setParameter(4,$transferencista['id'])
                                            ->getQuery()->execute();



                                            $tarea->registralogProveedor($informe[$cantArchivos]['archivo']['name'],count($informe[$cantArchivos][$drogueria['codigo']]),$drogueria['codigo'],1,$informe[$cantArchivos][$drogueria['codigo']]['pedido'],$totalPedido['total'] ,$informe[$cantArchivos][$drogueria['codigo']]['canTotal'],$arrayProveedoresFtp[$proveedor['nit']]);
                                       
                                            //Enviar correo de creación de pedido a Convenios.
                                            if( isset($drogueriasArray[$drogueria['codigo']]['drogueria']) && isset($drogueriasArray[$drogueria['codigo']]['cupo'] ) ){

                                                if($this->enviarCorreo($productosRegistrados, $pedido->getConsecutivo(), $pedido->getProveedor()->getNombre(), $drogueriasArray[$drogueria['codigo']]['drogueria'], $drogueria['codigo'], $drogueriasArray[$drogueria['codigo']]['cupo'])){

                                                    $tarea->registralog('Se envio correo para el pedido : '.$pedido->getConsecutivo(),$session->get('administradorId'));

                                                }else{

                                                    $tarea->registralog('Se creo el pedido : '.$pedido->getConsecutivo().' Pero no fue posible enviar el correo de confirmacion. Revise la configuracion del correo.',$session->get('administradorId'));
                                                }
                                            }else{

                                                $tarea->registralog('Se creo el pedido : '.$pedido->getConsecutivo().' Pero no fue posible enviar el correo de confirmacion. Info del Asociado incompleta.',$session->get('administradorId'));

                                            }
                                            

                                        }else{
                                            $informe[$cantArchivos][$drogueria['codigo']]=descartarDetallePedido($drogueria,$transferencista,$emc,$informe[$cantArchivos][$drogueria['codigo']]);
                                        }
                                    }else{
                                        if(isset($informe[$cantArchivos][$drogueria['codigo']])){

                                            if(isset($productosRegistrados[$drogueria['codigo']]))
                                                unset($productosRegistrados[$drogueria['codigo']]);


                                            $informe[$cantArchivos][$drogueria['codigo']]=$this->descartarDetallePedido($drogueria['codigo'],$transferencista['id'],$emc,$informe[$cantArchivos][$drogueria['codigo']]);
                                            $arrayEstado[$drogueria['codigo']]['noAceptado']= 1;
                                            $informe[$cantArchivos][$drogueria['codigo']]['totalPedido']=$totalPedido['total'];
                                        }
                                    }
                                }

                            }//fin foreach

                            unset($pedidoNocreado);
                            $informe[$cantArchivos]['pedidosTotales']=$pedidosTotales;

                            //Registrar informaciÃ³n de Ãºltimo pedido cargado, cantidad y Ãºltima transacciÃ³n con Convenios.
                            $this->updateProveedor($proveedor['nit'],$pedidosTotales, 1);

                            //Registrar actividad en logsAdministraodor
                            $tarea->registralog('3.3 Cargar '.$pedidosTotales.' pedido(s) para el proveedor['.$proveedor['nit'].'] ',$session->get('administradorId'));
                            //dump($informe);exit;
                            
                            $txt[$cantArchivos]=$tarea2->generarLog($informe[$cantArchivos],$path, $this->get('kernel')->getRootDir().'/../web/', $logRespuesta, $archivo);
                            $cantArchivos++;
                        }else{
                            //$informe['error'] = 'Por favor verifique la estructura del archivo['.$archivo.'].No fue posible iniciar la operaciÃ³n.';
                        }
                        
                    }else{
                        $informe[$cantArchivos]['error'] = 'Error. Verifique la ruta y el nombre del archivo['.$archivo.'].';
                        $tarea->registralog('Error. Verifique la ruta y el nombre del archivo['.$dir.$archivo.'] ',$session->get('administradorId'));
                    }

                    //mover el archivo a la carpeta procesados    
                    $tarea2->transaccionFTP($dir.$archivo,$archivo,$path.'procesados/');

                    fclose($fp);
                    @unlink($dir.$archivo);
                    @ftp_delete($conexionFTP,$archivo);
                }//fin foreach
            }else{
                $sinArchivos[]='El proveedor  '.$proveedor['proveedor'].' .No tiene archivos para procesar. '; 
            }  
        }

        // se actualizan pedidos pendientes y se crean archivos para actualizar 
        $this->conveniosPedidosPendientesActualizar($pedidosGenerados, $path, $arrayProveedoresFtp[$proveedor['nit']]);

        ftp_close($conexionFTP);
        return array('informe' =>$informe ,'log'=>$txt,'sinArchivos'=>$sinArchivos);
    }

    /**
        * Acción que envía, a los administradores con seguimiento, información sobre el pedido creado.
        * @param $productos=Información de los productos insertados  ,$consecutivo=Consecutivo del pedido creado
        * @return Objeto de tipo PedidoDescripcion.
        * @author Julian casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    private function enviarCorreo($productos,$consecutivo,$proveedorNombre,$clienteNombre,$clienteCodigo,$cupo){

        $message='';

        if( $this->message == '' ){

            $this->message=new \Swift_Message();
            $this->message->setSubject('Pedido cargado por FTP a Convenios ');
            $this->message->setFrom($this->container->getParameter('administrador_email'),$this->container->getParameter('administrador_nombre'));
            $this->message->setPriority(1);
            $this->message->setContentType('text/html');


            $em=$this->getDoctrine()->getManager();
            //$this->administradores=$em->getRepository('FTPAdministradorBundle:Administrador')->findBySeguimiento(1);

            $this->message->setTo('j.casas@waplicaciones.co','Julián Casas.');
            /*foreach ($this->administradores as $administrador) {
                $this->message->setTo($administrador->getEmail(),$administrador->getNombre());
            }*/
            $this->$message->addBcc($this->container->getParameter('administrador_email'),$this->container->getParameter('administrador_nombre'));

        }

        $message = $this->message;
        $imageLogo2 = $message->embed(\Swift_Image::fromPath('img/nuevo_logo_copi.png'));    

        set_time_limit(120);
        $template= $this->renderView('FTPAdministradorBundle:Proveedor:emailPedido.html.twig',
            array(
                'detalle' => $productos,'codPedido' =>$consecutivo,'logoCopi'=>$imageLogo2,
                'proveedorNombre'=>$proveedorNombre,'clienteNombre'=>$clienteNombre,'clienteCodigo'=>$clienteCodigo,
                'cupo'=>$cupo
                ));
        $message->setBody($template);
        try{
            $this->get('mailer')->send($message);
            return true;
        }catch(\Exception $e){
            $data['noEnviados'] = 1;
            return false;
        }
    }

 
    
    public function descartarDetallePedido($drogueria,$transferencista,$emc,$informe){

        $data=$emc->createQueryBuilder()->select('pd.productoCodigoBarras AS pdtoCodigoBarras')
        ->from('FTPAdministradorBundle:PedidoDescripcion','pd')
        ->where('pd.pedido IS NULL AND pd.drogueriaId=?1 AND pd.transferencista=?2')
        ->setParameter(1,$drogueria)
        ->setParameter(2,$transferencista)
        ->getQuery()->getResult();
        
        foreach ($data as $k => $v) {
            if(in_array($v['pdtoCodigoBarras'], $informe['insertados'])){
                unset($informe['insertados'][$v['pdtoCodigoBarras']]);
                $informe['noIsertados'][]='El total del pedido no alcanzaba el mÃ­nimo necesario. '.$v['pdtoCodigoBarras'];
                
                $emc->createQueryBuilder()->delete('FTPAdministradorBundle:PedidoDescripcion','pd')
                ->where('pd.pedido IS NULL AND pd.drogueriaId=?1 AND pd.transferencista=?2')
                ->setParameter(1,$drogueria)
                ->setParameter(2,$transferencista)
                ->getQuery()->execute();
            }  
        }
        if(empty($informe['insertados']))
            unset($informe['insertados']);
        return $informe;
    }


    /**
        * AcciÃ³n que crea un nuevo detalle del pedido segÃºn la informaciÃ³n del inventario.
        * @param [
        *           $pedidoDescripcion= Instancia de PedidoDescripcion,
        *           $inventario= Instancia de InvantarioProdcutos,
        *           $idDrogueria = Id de la droguerÃ­a a la cual se asocia el detalle del prodcuto,
        *           $canditad= Cantidad solicitada del detalle del producto,
        *           $emc= Contiene el EntityManager,
        *           $codigoAsociado= CÃ³digo del asociado.
        *           $idTransferencista= Id del transferencista segÃºn proveedor.
        *        ]
        * @return Objeto de tipo PedidoDescripcion.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    protected function crearPedidosDescripcion($pedidoDescripcion, $inventario, $idDrogueria, $cantidad,$emc,$codigoAsociado,$idTransferencista) {

        $session = $this->getRequest()->getSession();
        $pedidoDescripcion->setPdtoId($inventario->getId());// id del producto.
        $pedidoDescripcion->setDrogueriaId($codigoAsociado);
        $pedidoDescripcion->setTransferencista($emc->getReference('FTPAdministradorBundle:Transferencista',$idTransferencista));
        $pedidoDescripcion->setCantidadPedida($cantidad);
        $pedidoDescripcion->setProductoCodigo($inventario->getCodigo());
        $pedidoDescripcion->setProductoCodigoBarras($inventario->getCodigoBarras());
        $pedidoDescripcion->setProductoDescripcion($inventario->getDescripcion());
        $pedidoDescripcion->setProductoPresentacion($inventario->getPresentacion());
        $pedidoDescripcion->setProductoPrecio($inventario->getPrecio());
        $pedidoDescripcion->setProductoMarcado($inventario->getMarcado());
        $pedidoDescripcion->setProductoReal($inventario->getPrecioReal());
        $pedidoDescripcion->setProductoIva($inventario->getIva());
        $pedidoDescripcion->setLinea($emc->getReference('FTPAdministradorBundle:Linea',$inventario->getLinea()->getId()));
        $pedidoDescripcion->setProductoDescuento($inventario->getDescuento());
        $pedidoDescripcion->setProductoFecha($inventario->getFechaCreado());
        $pedidoDescripcion->setProductoEstado(0);
        $pedidoDescripcion->setCantidadFinal($cantidad);
        $pedidoDescripcion->setProductoFoto($inventario->getFoto());

        return $pedidoDescripcion;
    }

    /**
        * AcciÃ³n que actualiza las fechas y la cantidad de pedidos generados a Convenios para el proveedor indicado.
        * @param [
        *           $nitProveedor= NIT del proveedor a actualizar.
        *           $pedidosTotales= Cantidad de pedidos creados para el proveedor.
        *           $tipo=[1:convenios,2:sipproveedores]
        *        ]
        * @return 
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    private function updateProveedor($nitProveedor,$pedidosTotales,$tipo){

        $em=$this->getDoctrine()->getManager();
        $campo=$id='';

        if($tipo==1){
            $campo='p.ultCargueConvenios';
            $id='p.nitProveedor';
        }else{
            $campo='p.ultCargueTransferencista';
            $id='p.codigoProveedor';
        }

        $date=new \DateTime('now');
        $date=$date->format('Y-m-d H:i:s');
        $em->createQueryBuilder()
        ->update('FTPAdministradorBundle:Proveedores','p')
        ->set('p.ultimosPedidos','?1')
        ->set('p.cantidad','?2')
        ->set($campo,'?3')
        ->where($id.'=?4')
        ->setParameter(1,$date)
        ->setParameter(2,$pedidosTotales)
        ->setParameter(3,$date)
        ->setParameter(4,$nitProveedor)
        ->getQuery()->execute();
    }

      /**
        * Renderiza a la vista con la plantilla para mostrar el informe de procesar ,a Convenios, para el proveedor seleccionado
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
        * @Template ("FTPAdministradorBundle:Proveedor:informeUnProveedor.html.twig")
    */
    public function informeAction(Request $request){
       
    
        $array=$request->get('data');
        $arrayData=$array['informe']['informe'];
        $arrayLog=(isset($array['informe']['log']))?$array['informe']['log']:false;
        $pedidosTotales=(isset($arrayData['pedidosTotales']))?$arrayData['pedidosTotales']:false;
        $noLeidas=(isset($arrayData['noLeidas']))?$arrayData['noLeidas']:false;
        $archivo=(isset($arrayData['archivo']))?$arrayData['archivo']:false;
        $drogNoEncontradas=(isset($arrayData['noEncontradas']))?$arrayData['noEncontradas']:false;
        $drogPruebas=(isset($arrayData['pruebas']))?$arrayData['pruebas']:false;
        $totalPedido=(isset($arrayData['totalPedido']))?$arrayData['totalPedido']:false;
        //dump($array);exit;
        unset($arrayData['noLeidas'],$arrayData['pedidosTotales'],$arrayData['archivo'],$arrayData['noEncontradas'],$arrayData['log'],$array['informe']['log'],$arrayData['pruebas']);
        
        return array(   
                    'informe'=>$arrayData,
                    'pedidosTotales'=>$pedidosTotales,
                    'noLeidas'=>$noLeidas,
                    'archivo'=>$archivo,
                    'drogNoEncontradas'=>$drogNoEncontradas,
                    'logs'=>$arrayLog,
                    'pruebas'=>$drogPruebas,
                    'totalPedido'=>$totalPedido
                );
    }


    private function pedidoSipProveedores($path,$session,$archivo,$ruta){

        //EntityManager
        $emsp=$this->getDoctrine()->getManager('sipproveedores');
        //variables de entorno
        $informe=array();
        $tarea=$this->container->get('utilidadesAdministrador');
        $pesoMaxPermitido = 3000;
        $novedad=$archivo;

        $user=$this->container->getParameter('user_ftp');
        $pass=$this->container->getParameter('pass_ftp');
        $host=$this->container->getParameter('host_ftp');
        
            $fp = @fopen($path.$archivo, 'rb');
            $peso = @ftp_size($fp) / 1000;
            if ($peso < $pesoMaxPermitido){
                if($fp){
                    //variables de entorno
                    $contLn=1; 
                    $transferencistas=array();
                    $droguerias=array();
                    $arrayPedidosCreados=array();
                    
                    $arrayConsecutivos=array();
                    $datosPedido=array();
                    $insertados=array();
                    $noLeidas=array();
                    $reporte= array();
                    $arryAuxDrog=array();
                    $drogueria='';
                    $arrayAuxProv=array();
                    $proveedor='';
                    $auxArray=array();
                    $pedidosLimpiados=array();
                    //Mientras haya filas que leer en el archivo
                    while ($datos = fgetcsv($fp, 1000, ";")){
                        if(isset($datos[0],$datos[1],$datos[2],$datos[3],$datos[4],$datos[6],$datos[7])){
                            $datos[0] = (int)trim($datos[0]); /* DroguerÃ­a */
                            $datos[1] = stripslashes(trim($datos[1])); /* Tipo Pedido */
                            $datos[2] = stripslashes(trim($datos[2])); /* Material */
                            $datos[3] = (int) trim($datos[3]); /* Cantidad */
                            $datos[4] = stripslashes(trim($datos[4])); /* Lote */
                            $datos[6] = stripslashes(trim($datos[6])); /* Centro */
                            $datos[7] = stripslashes(trim($datos[7])); /* Ttransferencista */
                            
                            //Se valida si la fila se puede procesar.
                            if($datos[0] && ($datos[3]>0 && $datos[3]<999)){//Para validar si la cantidad corresponde a un entero

                                    //Si el array de droguerías esta vacio o si no existe el puntero en el array consulta.
                                    if(empty($arrayAuxProv)||(!isset($arrayAuxProv[$datos[0]]))){
                                        //Proveedor
                                        $proveedor=$emsp->createQueryBuilder()
                                                            ->select('p.codigoCopidrogas,p.id')
                                                            ->from('SipproveedoresEntity:Transferencista','t')
                                                            ->join('SipproveedoresEntity:Proveedor','p','WITH','p.id=t.idProveedor')
                                                            ->where('t.id=?1')
                                                            ->setParameter(1,$datos[7])
                                                            ->getQuery()
                                                            ->getOneOrNullResult();

                                        $arrayAuxProv[$datos[0]]=array(
                                                                    'codigoCopidrogas' =>$proveedor['codigoCopidrogas'],
                                                                    'id' =>$proveedor['id']
                                                                );              
                                    }else{
                                        $proveedor=$arrayAuxProv[$datos[0]];
                                    } 

                                    if($proveedor){
                                        $transferencistas[$datos[0]][0]=$datos[7];
                                        $transferencistas[$datos[0]][1]=$proveedor["codigoCopidrogas"];
                                        $transferencistas[$datos[0]][2]=$proveedor["id"];
                                        
                                    }else{
                                       $noLeidas[]='Fila : '.$contLn.'. Transferencista No Encontrado '.$datos[7];
                                       $reporte[] = "Transferencista no encontrado: " . $datos[7];
                                        if(!isset($auxArray[$datos[7]])){
                                            $novedad.=' Transferencista No Encontrado '.$datos[7];
                                            $auxArray[$datos[7]]=$datos[7];
                                        }
                                    }

                                //Si el array de droguerías esta vacio o si no existe el puntero en el array consulta.
                                if(empty($arryAuxDrog)||(!isset($arryAuxDrog[$datos[0]]))){
                                    //Droguería
                                    $drogueria=$emsp->createQueryBuilder()
                                                    ->select('c.codigo,c.cupoAsociado,c.drogueria,c.centro')
                                                    ->from('SipproveedoresEntity:Cliente','c')
                                                    ->where('c.codigo=?1')
                                                    ->setParameter(1,$datos[0])
                                                    ->getQuery()
                                                    ->getOneOrNullResult();
                                    
                                    $arryAuxDrog[$drogueria['codigo']]= array(
                                                                                'codigo' =>$drogueria['codigo'],
                                                                                'cupoAsociado' =>$drogueria['cupoAsociado'],
                                                                                'drogueria' =>$drogueria['drogueria'],
                                                                                
                                                                            );                
                                }else{
                                    $drogueria=$arryAuxDrog[$datos[0]];
                                }
                                    
                                    if($drogueria){
                                        
                                            $droguerias[$datos[0]]['codigo']=$drogueria['codigo'];
                                            $droguerias[$datos[0]]['drogueria']=$drogueria['drogueria'];
                                            $droguerias[$datos[0]]['crearP']=(!isset($arrayPedidosCreados[$datos[0]]))?true:false;
                                            $droguerias[$datos[0]]['idTrans']=$datos[7];   
                                        

                                    }else{
                                       $noLeidas[]='Fila : '.$contLn.'. Codigo de drogueria no encontrado '.$datos[0];
                                       $reporte[] = 'Codigo de drogueria no encontrado: '.$datos[0];
                                        if(!isset($auxArray[$datos[0]])){
                                            $novedad.='Codigo de drogueria No Encontrado '.$datos[0];
                                            $auxArray[$datos[0]]=$datos[0];
                                        }
                                    }
                                    if(isset($arryAuxDrog[$datos[0]],$arrayAuxProv[$datos[0]],$transferencistas[$datos[0]][1])){

                                        //Limpiar pedido
                                        //Elimnar todos los productos que tengan estado 0  y el id_transferencista = $datos[7] 
                                        if(!(isset($pedidosLimpiados[$datos[7].$datos[0]]))||empty($pedidosLimpiados)){
                                            $pedidosLimpiados[$datos[7].$datos[0]]=$datos[7].$datos[0];

                                            $eliminar = $emsp->createQuery(" DELETE FROM SipproveedoresEntity:DescripcionPedido dp WHERE dp.estadoPedido =:estado AND dp.idTransferencista=:idTransferencista AND dp.codigoAsociado=:dorgueria")
                                            ->setParameter('estado', 0)
                                            ->setParameter('idTransferencista',$datos[7])
                                            ->setParameter('dorgueria',$datos[0])
                                            ->getResult();
                                            if($eliminar>0){
                                                $logAdmin=$this->container->get('utilidadesAdministrador');
                                                $logAdmin->registralog('CSV - Limpia pedido se eliminaron [' .$eliminar. '] productos. Trans ['.$datos[7].']. Drog['.$datos[0].']',$session->get('idAdministrador')); 
                                            }
                                            unset($eliminar,$logAdmin);

                                        }

                                        // Si la columna LOTE esta vacia no se evalua la columna LOTE en la consulta.
                                        $lote=($datos[4])?' AND i.lote=:lote ':'';
                                        $query=$emsp->createQuery('SELECT i FROM SipproveedoresEntity:Inventario i WHERE i.precioReal IN '
                                            .'( SELECT MIN(ia.precioReal) FROM SipproveedoresEntity:Inventario ia WHERE ia.centro=:centro AND ia.material=:material AND i.proveedorId=:proveedor '.$lote.') '
                                            .' AND i.centro=:centro AND i.material=:material AND i.proveedorId=:proveedor '.$lote.'ORDER BY i.precioReal ASC');
                                        $query->setParameter('centro',$datos[6]);
                                        $query->setParameter('material',$datos[2]);
                                        $query->setParameter('proveedor',$transferencistas[$datos[0]][1]);
                                        $query->setMaxResults(1);
                                       // $query->orderBy('i.precioReal', 'ASC');
                                        if($lote!=''){
                                            $query->setParameter('lote',$datos[4]);
                                        }else{                                       
                                            $novedad.=' <br>Fila : '.$contLn.'Mareaial sin lote : '.$datos[2].'.Centro : '.$datos[6].'<br>';                                      
                                        }
                                        $producto=$query->getOneOrNullResult();
                                        unset($query);
                                        if($producto){

                                    
                                            if(!(isset($arrayProductos[$datos[0]][$producto->getMaterial()]))||(empty($arrayProductos))){
                                                //Se inserta producto encontrado
                                                $estado=$this->insertarProducto($producto, $datos[3], $datos[0], $datos[7], 0,$datos[1]);

                                                if($estado){
                                                    $arrayProductos[$datos[0]][$producto->getMaterial()]=$producto->getMaterial();
                                                    $insertados[$datos[0]]['pedidos'][]=$datos[2].' ['.$producto->getDenominacion().'] ';
                                                }else{
                                                    $noLeidas[]=$datos[2];
                                                }
                                            }
                                           
                                        }else{
                                            //Consultar en inventario kits.
                                            $producto=$emsp->createQueryBuilder()
                                                    ->select('ik')
                                                    ->from('SipproveedoresEntity:InventarioKits','ik')
                                                    ->where('ik.centro=?1 AND ik.codigo LIKE ?2')
                                                    ->setParameter(1,$datos[6])
                                                    ->setParameter(2,$datos[2])
                                                    ->setMaxResults(1)
                                                    ->getQuery()
                                                    ->getOneOrNullResult();
                                           if($producto){

                                                $cantidadKit=$totalPrecio=0;

                                                $detallekits=$emsp->createQueryBuilder()
                                                             ->select('dk')
                                                             ->from('SipproveedoresEntity:DetalleKits','dk')
                                                             ->where('dk.centro=?1 AND dk.codigoKit=?2')
                                                             ->setParameter(1,$datos[6])
                                                             ->setParameter(2,$datos[2])
                                                             ->getQuery()
                                                             ->getResult();
                                                foreach ($detallekits as $key) {
                                                    $totalPrecio += str_replace(' ','', $key->getCampo2());
                                                    $cantidadKit += $key->getDescripcion();
                                                }  
                                              
                                                if(!(isset($arrayProductos[$datos[0]][$producto->getCodigo()]))||(empty($arrayProductos))){
                                                    //Se inserta producto encontrado
                                                    $estado=$this->insertarKitPrepack($producto, $datos[3], $cantidadKit, $totalPrecio, $datos[0],  $datos[1], $datos[7], 'Kit',0);
                                                    if($estado){
                                                        $arrayProductos[$datos[0]][$producto->getCodigo()]=$producto->getCodigo();
                                                        $insertados[$datos[0]]['kits'][]=$datos[2].'['.$producto->getNombre().']';
                                                    }else{
                                                        $noLeidas[]=$datos[2];
                                                    }
                                                } 
                                                unset($producto); 

                                            }else{
                                                //Kit no fue encontrado y se busca Prepack
                                                $prepack=$emsp->createQueryBuilder()
                                                     ->select('ip')
                                                     ->from('SipproveedoresEntity:InventarioPrepacks','ip')
                                                     ->where('ip.centro=?1 AND ip.codigo LIKE ?2 ')
                                                     ->setParameter(1,$datos[6])
                                                     ->setParameter(2,$datos[2])
                                                     ->setMaxResults(1)
                                                     ->getQuery()
                                                     ->getOneOrNullResult();

                                                if($prepack){

                                                    $cantidadKit=$totalPrecio=0;
                                                    $detalleKit =$emsp->createQueryBuilder()
                                                                ->select('dk')
                                                                ->from('SipproveedoresEntity:DetallePrepacks','dk')
                                                                ->where('dk.centro=?1 AND dk.codigoPrepack=?2')
                                                                ->setParameter(1,$datos[6])
                                                                ->setParameter(2,$datos[2])
                                                                ->getQuery()
                                                                ->getResult();

                                                    foreach ($detalleKit as $key) {
                                                        $totalPrecio += str_replace(' ', '', $key->getCampo2());
                                                        $cantidadKit += $key->getDescripcion();
                                                    }


                                                    if(!(isset($arrayProductos[$datos[0]][$prepack->getCodigo()]))||(empty($arrayProductos))){
                                                        //Se inserta producto encontrado
                                                        $estado=$this->insertarKitPrepack($prepack, $datos[3], $cantidadKit, $totalPrecio, $datos[0],  $datos[1], $datos[7], 'Kit',0);
                                                        if($estado){
                                                            $arrayProductos[$datos[0]][$prepack->getCodigo()]=$prepack->getCodigo();
                                                            $insertados[$arrayConsecutivos[$datos[0]]]['prepack'][]=$datos[2].'['.$prepack->getNombre().']';
                                                        }else{
                                                            $noLeidas[]=$datos[2];
                                                        }
                                                    }  
                                                }else{
                                                    //No encuentra producto, kit o prepack
                                                      $noLeidas[]= "El producto " . $datos[2].' para la drogueria '.$datos[0].' del centro '.$datos[6].' no fue encontrado.Linea '.$contLn;
                                                      $reporte[]='Producto No Encontrado, Codigo: ' . $datos[2];
                                                      $novedad.="El producto " . $datos[2].' para la drogueria '.$datos[0].' del centro '.$datos[6].' no fue encontrado.Línea '.$contLn;
                                                }
                                            }
                                        }
                                    }
                                 
                            }else{
                                if($datos[3]<=0 && $datos[3]>999)
                                    $reporte[]='La cantidad solicitada no es valida. Codigo: ' . $datos[2] .' Solicitado: ' . $datos[3];
                                
                                $noLeidas[]= 'Verifique el codigo de la drogueria o la cantidad en la linea '.$contLn; 
                                $novedad.='Verifique el código de la droguería o la cantidad en la línea '.$contLn; 
                            }  
                            $contLn++; 
                        }else{
                           $informe['errorArchivo']='Error en la estructura del archivo '.$archivo.'.'; 
                           $novedad.='Error en la estructura del archivo '.$archivo.'.';
                        } 
                    }//fin while 

                    //Recuperar Droguerías procesadas.
                    $conexionSP=$emsp->getConnection();
                    $sql=" SELECT DISTINCT `clasificacion`, `proveedor_id` AS proveedor, `codigo_asociado` AS codigo, `id_transferencista` AS idTransferencista FROM `descripcion_pedido` WHERE `estado_pedido` = 7 ";
                    $conexionSP->query($sql);
                    $sqlDroguerias=$conexionSP->query($sql);
                    $sqlDroguerias=$sqlDroguerias->fetchAll();

                    //dump($sqlDroguerias);exit();

                    $drogueriasProcesadas=array();
                    foreach ($sqlDroguerias as $value) {
                        $drogueriasProcesadas[$value['codigo'].$value['proveedor'].$value['clasificacion'].$value['idTransferencista']]['codigo']=$value['codigo'];
                        $drogueriasProcesadas[$value['codigo'].$value['proveedor'].$value['clasificacion'].$value['idTransferencista']]['proveedor']=$value['proveedor'];
                        $drogueriasProcesadas[$value['codigo'].$value['proveedor'].$value['clasificacion'].$value['idTransferencista']]['clasificacion']=$value['clasificacion'];
                        $drogueriasProcesadas[$value['codigo'].$value['proveedor'].$value['clasificacion'].$value['idTransferencista']]['idTransferencista']=$value['idTransferencista'];
                    }

                    unset($sqlDroguerias, $value);

                    //dump($drogueriasProcesadas);exit();

                    $countPedidosCreados=0;

                    foreach ($drogueriasProcesadas as $drogueria){

                            $pedidoDescripcion=$emsp->createQueryBuilder()
                            ->select('COUNT(dp.id) totalProductos, SUM((dp.precioReal * dp.cantidadPedida)+((dp.precioReal * dp.cantidadPedida * dp.impuesto)/100))  totalPedido')
                            ->from('SipproveedoresEntity:DescripcionPedido','dp')
                            ->where(' dp.estadoPedido=:estadoPedido')
                            ->andWhere('dp.codigoAsociado=:codigoAsociado')
                            ->andWhere('dp.idTransferencista=:idTransferencista')
                            ->setParameter(':estadoPedido',7)
                            ->setParameter(':codigoAsociado',$drogueria['codigo'])
                            ->setParameter(':idTransferencista',$drogueria['idTransferencista'])
                            ->getQuery()
                            ->getOneOrNullResult();

                            $pedido=new Pedidos();
                            $pedido->setFechaConfirmado(new \DateTime('now'));
                            $pedido->setEstado(1);
                            $pedido->setTipo('ftp');
                            $pedido->setCupo(0);
                            $pedido->setCodigoAsociado($drogueria['codigo']);
                            $pedido->setCantidadProductos($pedidoDescripcion['totalProductos']);
                            $pedido->setPrecioPedido(intval($pedidoDescripcion['totalPedido']));
                            $pedido->setCodigoPedido($drogueria['proveedor']);
                            $pedido->setIdTransferencista($drogueria['idTransferencista']);
                            $pedido->setEvento(($drogueria['clasificacion'] == 'ZPTR') ? 0 : -1);


                            $emsp->persist($pedido);
                            $emsp->flush();

                            $pedido->setCodigoPedido($drogueria['proveedor'].'-'.$pedido->getId());
                            $emsp->persist($pedido);
                            $emsp->flush();

                            $datosPedido[$pedido->getCodigoPedido()]['totalPedido']=intval($pedidoDescripcion['totalPedido']);
                            $datosPedido[$pedido->getCodigoPedido()]['totalProductos']=$pedidoDescripcion['totalProductos'];
                            $datosPedido[$pedido->getCodigoPedido()]['drogueria']=$drogueria['codigo'];                                   

                            $countPedidosCreados++;

                            $droguerias[$drogueria['codigo']]['pedido']=$pedido->getCodigoPedido();  
                            $arrayPedidosCreados[$drogueria['codigo']]=$drogueria['codigo'];
                            $arrayConsecutivos[$drogueria['codigo']][]=$pedido->getCodigoPedido();

                                            
                            $emsp->createQueryBuilder()
                                 ->update('SipproveedoresEntity:DescripcionPedido','dp')
                                 ->set('dp.estadoPedido','?1')
                                 ->set('dp.idPedido','?2')
                                 ->where('dp.estadoPedido=?3 AND dp.idTransferencista=?4 AND dp.codigoAsociado=?5 ')
                                 ->setParameter(1,'1')
                                 ->setParameter(2,$pedido->getId())
                                 ->setParameter(3,7)
                                 ->setParameter(4,$pedido->getIdTransferencista())
                                 ->setParameter(5,$drogueria['codigo'])
                                 ->getQuery()
                                 ->execute();
                            
                            $tarea->registralogProveedor($archivo,0,$drogueria['codigo'],1,$pedido->getCodigoPedido(),$pedidoDescripcion['totalPedido'] ,$pedidoDescripcion['totalProductos'],$drogueria['proveedor']);
                            $reporte[] = 'Pedido: ' .$pedido->getCodigoPedido().' NumProductos:'.$pedidoDescripcion['totalProductos'].' Drogueria:'. $drogueria['codigo'];
                            unset($pedido);
                    }//fin foreach

                    @fclose($fp);
                    unset($transferencistas,$droguerias,$pedido);  

                }else{
                    $informe['errorArchivo']='No se encontro el archivo '.$archivo.'.';
                    $reporte[] ='No se encontro el archivo '.$archivo.'.';
                    $novedad.='No se encontro el archivo '.$archivo.'.';
                }
            }else{
              $novedad.='El archivo exede el peso maximo 3MB. ('.$peso.')';
            } 

            $logAdmin=$this->container->get('utilidadesAdministrador');
            $logAdmin->registralog('Novedad: '.$novedad,$session->get('idAdministrador')); 
 

            $nomFile='Reporte_'. $archivo . "_" . date("dmYHis") . ".txt";
            $fileHandle = fopen($path.$nomFile, 'w');
            foreach ($reporte as $val){
                fwrite($fileHandle, $val . "\r\n");
            }
            fclose($fileHandle);     

            $tarea=$this->container->get('generarArchivos');
            $tarea->transaccionFTP($path.$nomFile,$nomFile,$ruta.'/procesados/');
  
        if(isset($informe['errorArchivo'])){
          return $informe; 
        }else{
            $informe['consecutivos']=$arrayConsecutivos;
            $informe['datosPedido']= $datosPedido;
            $informe['insertados']=$insertados;
            $informe['noLeidas']=$noLeidas;
        }



        return $informe;
                  
    }//fin function

    /**
        * AcciÃ³n que inserta un nuevo producto en DetallePedido
        * @param [
        *           $producto= Instancia de tipo Inventario.
        *           $cantidadPedida= Cantidad del producto pedida.
        *           $drogueria=CÃ³digo de la droguerÃ­a.
        *           $idTransferencista.
        *           $idPedido.
        *           $plazo
        *        ]
        * @return 
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    private function insertarProducto($producto, $cantidadPedida, $drogueria, $idTransferencista, $idPedido, $plazo){
        $emsp=$this->getDoctrine()->getManager('sipproveedores');

        if($plazo == "ZPEV"){
            $plazo = "ZPEV";
        }else{
            $plazo = "ZPTR";
        }

        $descripcionPedido=new DescripcionPedido();
        $descripcionPedido->setCentro($producto->getCentro());
        $descripcionPedido->setClasificacion($plazo);
        $descripcionPedido->setProveedor($producto->getProveedor());
        $descripcionPedido->setProveedorId($producto->getProveedorId());
        $descripcionPedido->setMaterial($producto->getMaterial());
        $descripcionPedido->setDenominacion($producto->getDenominacion());
        $descripcionPedido->setLote($producto->getLote());

        $descripcionPedido->setEmpaque($producto->getEmpaque());
        $descripcionPedido->setCantidad($producto->getCantidad());
        $descripcionPedido->setPrecioReal($producto->getPrecioReal());
        $descripcionPedido->setPrecioCorriente($producto->getPrecioCorriente());

        $descripcionPedido->setPrecioMarcado($producto->getPrecioMarcado());
        $descripcionPedido->setImpuesto($producto->getImpuesto());
        $descripcionPedido->setBonificacion($producto->getBonificacion());
        $descripcionPedido->setTiempo(new \DateTime('now'));

        $descripcionPedido->setFechaIngreso($producto->getFechaIngreso());
        $descripcionPedido->setPlazo($plazo);
        $descripcionPedido->setCodigoBarras($producto->getCodigoBarras());

        $descripcionPedido->setCodigoAsociado($drogueria);
        $descripcionPedido->setCantidadPedida($cantidadPedida);
        $descripcionPedido->setIdTransferencista($idTransferencista);
        $descripcionPedido->setEstadoPedido(7);//este estado se módifica cuando se crea el pedido.

        $descripcionPedido->setTipoSolicitud(1);
        $descripcionPedido->setTipoIngreso(1);

        $iva = 0;
        if (is_numeric(substr($producto->getImpuesto(), 0, 1))){
            $vec = explode('%', $producto->getImpuesto());
            if (!empty($vec[0])){
                if ($vec[0] != 0){
                    //$iva = "0." . str_replace("0", "", $vec[0]);
                    if(is_numeric($vec[0])){
                        $iva = $vec[0] / 100;
                    }
                }
            }
            unset($vec);
        }
        $descripcionPedido->setDescuentos(0);
        $descripcionPedido->setIdPedido($idPedido);
        $descripcionPedido->setDisponibilidad($producto->getDisponibilidad());
        $descripcionPedido->setObsequio($producto->getObsequio());
        $descripcionPedido->setIva($iva);

        $descripcionPedido->setTipoPedido('');
        $descripcionPedido->setExpedicion('');

        $emsp->persist($descripcionPedido);
        $emsp->flush();

        return $descripcionPedido->getId()?true:false;
  
    }  

    /**
        * AcciÃ³n que crea un nuevo pedido en el sistema.
        * @param [
        *           $idProveedor.
        *           $tipoPedido.
        *           $idTransferencista.
        *        ]
        * @return  codigo del pedido creado.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */

    private function crearPedido($idProveedor,$tipoPedido,$idTransferencista){
        
        $emsp=$this->getDoctrine()->getManager('sipproveedores');    
        $evento = ($tipoPedido == 'ZPTR') ? 0 : -1;
        
        $pedido=new Pedidos();
        $pedido->setEvento($evento);
        $pedido->setIdTransferencista($idTransferencista);
        $pedido->setCodigoPedido($idProveedor);

        $emsp->persist($pedido);
        $emsp->flush();

        $pedido->setCodigoPedido($idProveedor.'-'.$pedido->getId());

        $emsp->persist($pedido);
        $emsp->flush();
        return $pedido->getCodigoPedido();
    }   

    public function insertarKitPrepack($detallekits, $cantidadPedida, $cantidadKit, $totalPrecio, $drogueria,  $plazo, $transferencista, $tipoKitPrepack,$idPedido){
        $emsp=$this->getDoctrine()->getManager('sipproveedores');

        if($plazo == "ZPEV"){
            $plazo = "ZPEV";
        }else{
            $plazo = "ZPTR";
        }

        $pedidoDescripcion=new DescripcionPedido();

        $pedidoDescripcion->setCentro($detallekits->getCentro());
        $pedidoDescripcion->setClasificacion($plazo);
        $pedidoDescripcion->setProveedorId($detallekits->getCodCopiProveedor());
        $pedidoDescripcion->setProveedor($detallekits->getProveedor());
        $pedidoDescripcion->setMaterial($detallekits->getCodigo());

        $pedidoDescripcion->setDenominacion($detallekits->getDescripcion());
        $pedidoDescripcion->setLote($tipoKitPrepack);
        $pedidoDescripcion->setEmpaque($cantidadKit);
        $pedidoDescripcion->setCantidad($cantidadKit);
        $pedidoDescripcion->setPrecioReal($totalPrecio);

        $pedidoDescripcion->setPrecioCorriente($totalPrecio);
        $pedidoDescripcion->setImpuesto('');
        $pedidoDescripcion->setTiempo(new \DateTime());
        $pedidoDescripcion->setFechaIngreso($detallekits->getTiempo());
        $pedidoDescripcion->setPlazo($plazo);
        $pedidoDescripcion->setCodigoBarras('');

        $pedidoDescripcion->setCodigoAsociado($drogueria);
        $pedidoDescripcion->setCantidadPedida($cantidadPedida);
        $pedidoDescripcion->setCantidadProductos($cantidadKit);
        $pedidoDescripcion->setIdTransferencista($transferencista);
        $pedidoDescripcion->setEstadoPedido(7);//estado temporal de procesamiento FTP.

        $pedidoDescripcion->setTipoPedido('');
        $pedidoDescripcion->setExpedicion('');
        $pedidoDescripcion->setTipoSolicitud(1);
        $pedidoDescripcion->setTipoIngreso(1);
        $pedidoDescripcion->setDescuentos(0);
        $pedidoDescripcion->setIdPedido($idPedido);
        $pedidoDescripcion->setDisponibilidad(0);
        $pedidoDescripcion->setObsequio(0);
        $pedidoDescripcion->setIva(0);

        $emsp->persist($pedidoDescripcion);
        $emsp->flush();

        return $pedidoDescripcion->getId()?true:false;

        
    }

    /**
        * @param AcciÃ³n que registra el estado de las carpetas
        * @return json y/o cÃ³digo de estatus con el estado final de la tarea.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function estadoCarpeta($mensaje,$proveedor,$session){
        $tarea=$this->container->get('utilidadesAdministrador');
        $tarea->registralog('3.9'.$mensaje,$session->get('administradorId'));     
        unset($tarear);
    }

    public function transferenciasAction(Request $request){
        //echo "entra";exit();
        $response=new Response;
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(200);

        //procesar inventario
        $this->inventarioProveedoresHabilitados();

        $sipproveedores = $this->get('procesarArchivos');
        //para llamar las funciones que se neseciten 
        
        $json=array();
        //$json['template']=$sipproveedores->inicioCargaProveedores(true);

        $mensajeInterfaz = $sipproveedores->inicioCargaProveedores(true);
        $json['template']=$this->renderView('FTPAdministradorBundle:Proveedor:informeTodosProveedorTrans.html.twig', array(
            'mensajeInterfaz' => $mensajeInterfaz
          ));
        $json['app'] = "transferencista";
        
        
        $response->setContent(json_encode($json));

        return $response;
               
    }

    private function inventarioProveedoresHabilitados(){

        $em = $this->getDoctrine()->getManager();

        //Recuperar proveedores.
        $proveedores=$em->createQueryBuilder()
        ->select('p.proveedor,p.codigoProveedor,p.estadoTransferencia,p.carpetaTransferencista')
        ->from('FTPAdministradorBundle:Proveedores','p')
        ->getQuery()
        ->getArrayResult();

        $proveedores=$this->estadoSipProveedores($proveedores);
        if(isset($proveedores['habilitados'])){

            foreach ($proveedores['habilitados'] as $codigoProveedor=>$proveedor) {

                if($proveedor['carpeta'])
                    $this->cargarInventario( $codigoProveedor );


            }//end foreach
        }//end if


    }//end function

   
     /**
        * @param Object $request Objeto peticion de Symfony 2.6.
        * @return json y/o cÃ³digo de estatus con el estado final de la tarea.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function transferenciasAnteriorAction(Request $request){
        exit();
        $em=$this->getDoctrine()->getManager();
        $emsp=$this->getDoctrine()->getManager('sipproveedores');
        $session=$request->getSession();

        set_time_limit (0);
        ini_set('memory_limit', '1024M');

        $sipproveedores = $this->get('procesarArchivos');
        //para llamar la funcion que se nesecite $sipproveedores->inicioCargaProveedores();

        // Tipo de respuesta
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        //Variable de entorno
        $codigoProveedor=$request->get('data');
        $respuesta=array();
        $logFTP=array();
        $informe=array();
        $sinArchivos=array();
        $directorio=false;
        $proveedores=false;
        $archivosArray=false;
        $consecutivos=false;
        $datosPedido=false;
        $productosIngresados=false;
        $noLeidas=false;
        $inhabilitados=false;
        $carpetasCreadas=false;
        $proveedoresArray=false;
        $sinArchivos=false;
        $inventarioTxt=array();
        $carpeta=array();
        $logAdmin=$this->container->get('utilidadesAdministrador');
        $dir = $this->get('kernel')->getRootDir().'/../web/';


        //Llamar al servicio.
        $tarea=$this->container->get('generarArchivos');

        //Recuperar proveedores.
        $proveedores=$em->createQueryBuilder()
        ->select('p.proveedor,p.codigoProveedor,p.estadoTransferencia,p.carpetaTransferencista')
        ->from('FTPAdministradorBundle:Proveedores','p')
        ->getQuery()
        ->getArrayResult();

        $proveedores=$this->estadoSipProveedores($proveedores);
        if(isset($proveedores['habilitados'])){

            //conexion a FTP.
            $conexionFTP=@ftp_connect($this->container->getParameter('host_ftp'),$this->container->getParameter('port'));
            @ftp_login($conexionFTP,$this->container->getParameter('user_ftp'),$this->container->getParameter('pass_ftp'));


            foreach ($proveedores['habilitados'] as $codigoProveedor=>$proveedor) {
                if($proveedor['carpeta']){
                    $arrayArchivosAProcesar=array(); 
                    //Validamos si el directorio existe
                    if(!@ftp_chdir($conexionFTP,$proveedor['carpeta'])){
                        $raiz=explode('/',$proveedor['carpeta']);
                        if(!@ftp_chdir($conexionFTP,$raiz[0])){
                          @ftp_mkdir($conexionFTP,$raiz[0]);
                          @ftp_chdir($conexionFTP,$raiz[0]);
                        }
                        if(isset($raiz[1])){
                            if(!@ftp_chdir($conexionFTP,$raiz[1])){
                              @ftp_mkdir($conexionFTP,$raiz[1]);
                              @ftp_chdir($conexionFTP,$raiz[1]);
                            }
                        }else{
                            if(!@ftp_chdir($conexionFTP,'transferencias')){
                              @ftp_mkdir($conexionFTP,'transferencias');
                              @ftp_chdir($conexionFTP,'transferencias');
                            }
                        }

                        if(!@ftp_chdir($conexionFTP,'procesados')){
                            @ftp_mkdir($conexionFTP,'procesados');
                        }else{
                            @ftp_chdir($conexionFTP,'../');
                        }

                        if(!@ftp_chdir($conexionFTP,'inventario')){
                            @ftp_mkdir($conexionFTP,'inventario');
                        }else{
                            @ftp_chdir($conexionFTP,'../');   
                        }         
                    }
                    $ruta=ftp_pwd($conexionFTP);
                   
                    //Recuperamos los archivos disponibles.
                    $documentos = ftp_nlist($conexionFTP, ".");
                    
                    foreach ($documentos as $file) {
                        if(substr($file, -3)=='csv'||substr($file, -3)=='CSV'||
                           substr($file, -3)=='TXT'||substr($file, -3)=='txt' ){
                            $arrayArchivosAProcesar[]=$file;
                        }
                    } 
                    if(!$directorio){   
                        unset($documentos);
                        if($arrayArchivosAProcesar){
                            $contPeidos=0;
                            foreach($arrayArchivosAProcesar as $archivo){
                                @ftp_get($conexionFTP,$archivo,$archivo, FTP_BINARY);
                                $pedidos=$this->pedidoSipProveedores($dir,$session,$archivo,$ruta);
                               // $logFTP[$proveedor['proveedor']][$archivo]['log']=$tarea->logSipProveedores($archivo,$pedidos,$ruta.'/procesados/',$dir);
                                $logFTP[$proveedor['proveedor']][$archivo]['procesados']=$tarea->transaccionFTP($dir.$archivo,$archivo,$ruta.'/procesados/');
                                $informe[$proveedor['proveedor']][$archivo]=$pedidos;
                                @ftp_delete($conexionFTP,$archivo);
                                if(isset($pedidos['consecutivos']))
                                    $contPeidos+=count($pedidos['consecutivos']);
                            
                            } //fin foreach 
                            $this->registrarArchivos($arrayArchivosAProcesar,$proveedor['proveedor'],$session);
                            //Registrar información de último pedido cargado, cantidad y última transacción con SipPRoveedores.          
   
                            if($contPeidos>0){
                                $this->updateProveedor($codigoProveedor,$contPeidos,2);
                                $logAdmin->registralog('3.1.4 Cargar '.$contPeidos.' pedido(s) a SipProveedores. Proveedor['.$proveedor['proveedor'].'] ',$session->get('idAdministrador')); 
                            }
                        }else{
                            $sinArchivos[]='El proveedor '.$proveedor['proveedor'].' no tiene archivos para procesar.';
                        }

                    }else{
                        $carpetasCreadas[]='Se creo la carpeta de Transferencias para el proveedor '.$proveedor['proveedor'];
                        $directorio=false; 
                    }
                     //archivo asociados.
                    $asociados=$tarea->asociados($ruta.'/inventario/',$codigoProveedor,$dir);

                    //generar inventario
                    $inventario=$tarea->inventario($ruta.'/inventario/',$codigoProveedor,$dir);

                    //bonificaciones
                    $bonificaciones=$tarea->bonificaciones($ruta.'/inventario/',$codigoProveedor,$dir);

                    //kits
                    $kits=$tarea->kits($ruta.'/inventario/',$codigoProveedor,$dir);

                    //Detalle kits
                    $detallekits=$tarea->kitsDetalle($ruta.'/inventario/',$codigoProveedor,$kits['kits'],$dir);

                    $inventarioTxt[]=$proveedor['proveedor'].$asociados;
                    $inventarioTxt[]=$proveedor['proveedor'].$inventario;
                    $inventarioTxt[]=$proveedor['proveedor'].$bonificaciones;
                    $inventarioTxt[]=$proveedor['proveedor'].$kits['archivo'];
                    $inventarioTxt[]=$proveedor['proveedor'].$detallekits;
                    @ftp_chdir($conexionFTP,'../../');
                }else{
                   $carpeta[]='La carpeta para el proveedor ['.$proveedor['proveedor'].']. No se encuentra registrada en el sistema.'; 
                }    
                
            }//fin foreach 
        }//fin if
        $respuesta['informe']=$informe;
        
        if(isset($proveedores['inhabilitados']))
                $inhabilitados=$proveedores['inhabilitados'];  

        if($respuesta['informe']){           
            foreach ($respuesta['informe'] as $proveedor=>$archivos) { 
                $proveedoresArray[$proveedor]=$proveedor; 
                foreach ($archivos as $archivo=>$value ){
                    $archivosArray[$proveedor][$archivo]=$archivo;
                    if(isset($value['consecutivos'])){
                        $consecutivos[$proveedor][$archivo]=$value['consecutivos'];
                    }
                    if(isset($value['datosPedido'])){
                        $datosPedido[$proveedor][$archivo]=$value['datosPedido'];
                    }
                    if(isset($value['insertados'])){
                        $productosIngresados[$proveedor][$archivo]=$value['insertados'];
                    }
                    if(isset($value['noLeidas'])){
                        $noLeidas[$proveedor][$archivo]=$value['noLeidas'];
                    }
                }
            }
            $respuesta['template']=$this->renderView('FTPAdministradorBundle:Proveedor:informeTodosProveedorTrans.html.twig', array(
                'proveedores'=>$proveedoresArray,
                'archivos'=>$archivosArray,
                'consecutivos'=>$consecutivos,
                'datosPedido'=>$datosPedido,
                'productosInsertados'=>$productosIngresados,
                'noLeidas'=>$noLeidas,
                'inhabilitados'=>$inhabilitados,
                'estadoFTP'=>$logFTP,
                'carpetasCreadas'=>$carpetasCreadas,
                'sinArchivos'=>$sinArchivos,
                'inventarioTxt'=>$inventarioTxt,
                'carpetas'=>$carpeta
            ));
            $this->enviarCorreoProveedor($respuesta['template']);
        }else{
            $mensaje='<h1>Se inicio la carga de pedidos a SIPProveedores. El proceso se completó correctamente.</h1>';
            $this->enviarCorreoProveedor($mensaje); 
        }
                

        $respuesta['app']='transferencista'; 

       return $response->setContent(json_encode($respuesta));
        
        
    }

    /**
        * AcciÃ³n que verifica el estado de los proveedores para acceder a SipProveedores y los separa en un array con Ã­ndices [habilitado,deshabilitado]
        * @param $proveedores= array de proveedores recuperados por el sistema.
        * @return array con los proveedores habilitados y deshabilitados.
        * @author JuliÃ¡n casas <j.casas@waplicaciones.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    private function estadoSipProveedores($proveedores){
        $data=array();
        foreach ($proveedores as $k => $v) {
            if($v['estadoTransferencia']){
                $data['habilitados'][$v['codigoProveedor']]['proveedor']=$v['proveedor'];
                $data['habilitados'][$v['codigoProveedor']]['carpeta']=$v['carpetaTransferencista'];
            }else{
                 $data['inhabilitados'][$v['codigoProveedor']]=$v['proveedor'];
            }
        }
        return $data;
    }


    public function conveniosPedidosPendientesActualizar($idsPedidos, $path, $idProveedorConvenios){
        
        
        $directorio = true;
        $arrayArchivosAProcesar = array();
        $dir = $this->get('kernel')->getRootDir().'/../web/';
        $ems = $this->getDoctrine()->getManager('sipasociados');
        
        $dataClientes=array();
        
        $clientes=$ems->getRepository('FTPAdministradorBundle:Cliente')->findAll();
        
        foreach ($clientes as $cliente) {
            $dataClientes['centro'][$cliente->getCodigo()]=$cliente->getCentro();
            $dataClientes['drogueria'][$cliente->getCodigo()]=$cliente->getDrogueria();
            $dataClientes['asociado'][$cliente->getCodigo()]=$cliente->getAsociado();
            $dataClientes['ruta'][$cliente->getCodigo()]=$cliente->getRuta();
            $dataClientes['pCarga'][$cliente->getCodigo()]=$cliente->getPCarga();
            $dataClientes['direccion'][$cliente->getCodigo()]=$cliente->getDireccion();
            $dataClientes['telefono'][$cliente->getCodigo()]=$cliente->getTelefono();
        }
        
        /*$conexionFTP=@ftp_connect($this->container->getParameter('host_ftp'),$this->container->getParameter('port'));
        @ftp_login($conexionFTP,$this->container->getParameter('user_ftp'),$this->container->getParameter('pass_ftp'));
        
        if(!@ftp_chdir($conexionFTP,$path.'pendienteActualizar/')){
           $directorio=true; 
           ftp_mkdir($conexionFTP,$path.'pendienteActualizar/');
        }
        */
        if($directorio){
            
            //$documentos = ftp_nlist($conexionFTP, ".");

            foreach ($documentos as $file) {
                if(substr($file, -3)=='csv'||substr($file, -3)=='CSV' ){
                    $arrayArchivosAProcesar[]=$file;
                }
            }
            unset($documentos);
            
            if($arrayArchivosAProcesar){
                
                foreach($arrayArchivosAProcesar as $archivo){
                    
                    ftp_get($conexionFTP,$dir.$archivo,$archivo, FTP_BINARY);
                    
                    //respuesta de actualizacion de pedido con los archivos en la carpeta
                    $respuestaActualizacion = $this->actualizarPedidosConvenios($dir.$archivo, $idProveedorConvenios);
                    
                    if(count($respuestaActualizacion) > 0){
                        @unlink($dir.$archivo);
                        
                        //echo $conexionFTP;
                        if(@ftp_delete($conexionFTP, $archivo)){
                            
                            // creacion plantilla XLS
                            $this->archivoPedidosConfirmados($respuestaActualizacion, $idProveedorConvenios, $path, $dataClientes);
                        }

                    }
 
                }
            }
            
            // creacion de archivo de pedidos por actualizar con los ids de los pedidos que llegan
            $this->archivoPedidosPendientes($idProveedorConvenios, $idsPedidos, $path, $dataClientes);
                
        }
        
        ftp_close($conexionFTP);
    }
    
    
    public function actualizarPedidosConvenios($archivoLocal, $idProveedorConvenios){
        
        $emc = $this->getDoctrine()->getManager('convenios');
        $factura = array();
        
        if(filesize($archivoLocal)>0){
            $fp=fopen($archivoLocal,"r");
            $aux=0;
            if($fp){

                    $actualizacionPedido=$emc->createQuery('UPDATE FTPAdministradorBundle:Pedido p 
                                        SET p.estado=:estado,p.numeroCajas=:numeroCajas,p.fechaEnviadoProveedor=:enviadoProveedor,p.numeroFactura=:numeroFactura,
                                        p.remision=:remision,p.fechaRecibidoCopidrogas=:recibidoCopidrogas,p.fechaEnviadoAsociado=:enviadoAsociado,p.pedidoProcesado=:pedidoProcesado,p.fechaProcesado=:fechaProcesado
                                        WHERE p.consecutivo=:consecutivo');

                    $arrayFacturas = array();
                    while($data = fgetcsv($fp, 1000, ";")){
                        if($aux>=1){
                           if(($data[8]!='')){//funcion para validar datos.

                                    $pedido = $emc->getRepository('FTPAdministradorBundle:Pedido')->findOneByConsecutivo($data[8]);

                                    if($pedido){
                                        if($pedido->getPedidoProcesado() >= 1){

                                        }else{
                                            if($pedido->getFechaEnviadoProveedor() != $data[14]||
                                            $pedido->getFechaRecibidoCopidrogas() != $data[17] ||
                                            $pedido->getFechaEnviadoAsociado() != $data[19] ||
                                            $pedido->getNumeroFactura() != $data[15]){

                                                $actualizacionPedido->setParameter('estado', 1);
                                                $actualizacionPedido->setParameter('numeroCajas', ((is_numeric($data[12]))?$data[12]:$pedido->getNumeroCajas()));
                                                $actualizacionPedido->setParameter('enviadoProveedor', $data[14]);
                                                if ($data[15]!='' && $data[15]!=$pedido->getNumeroFactura()) {
                                                    $actualizacionPedido->setParameter('numeroFactura', $data[15]);
                                                }else{
                                                    $actualizacionPedido->setParameter('numeroFactura', $pedido->getNumeroFactura());
                                                }
                                                $actualizacionPedido->setParameter('remision', ($data[16]!='')?$data[16]:$pedido->getRemision());
                                                $actualizacionPedido->setParameter('recibidoCopidrogas', ($this->validarFecha($data[17]))?$data[17]:$pedido->getFechaRecibidoCopidrogas());
                                                $actualizacionPedido->setParameter('enviadoAsociado', ($this->validarFecha($data[19]))?$data[19]:$pedido->getFechaEnviadoAsociado());
                                                


                                                /**********Almacenamiento de facturas y remisiones**********/
                                                //datos[15] => #factura
                                                //datos[16] => #remision
                                                //datos[8]  => consecutivo del pedido
                                                if($data[15] != '' && $data[16] != '' && $data[16] != 0 && $data[8] != ''){
                                                    //if($pedido->getNumeroFactura() != $data[15] && $pedido->getPedidoProcesado() !=1 ){

                                                        //$values.=',pedido_procesado=0';
                                                        $actualizacionPedido->setParameter('pedidoProcesado',0);
                                                        $actualizacionPedido->setParameter('fechaProcesado', new \DateTime('now'));


                                                        $arrayFacturas[$data[15]]['remision'][]=$data[16];
                                                        //$arrayFacturas[$data[15]]['proveedor']=trim($data[0]);
                                                    //}

                                                }else{
                                                    $actualizacionPedido->setParameter('fechaProcesado', null);
                                                    $actualizacionPedido->setParameter('pedidoProcesado',null);
                                                }


                                                $actualizacionPedido->setParameter('consecutivo', $data[8])->execute();

                                                //$actualizado.='<br>Pedidocon consecutivo = <b>'.$data[8].'</b> actualizado <br>';

                                            }else{
                                                //$noActualizado.='<br>El pedido con consecutivo de barras = <b>'.$data[0].'</b> ya estaba actualizado.<br />';
                                                //$actualizado.='<br>El pedido con consecutivo de barras = <b>'.$data[8].'</b> ya estaba actualizado.<br />';
                                            }
                                        }

                                    }else{
                                        //$excepcion.='<br>El pedido con consecutivo =  <b>'.$data[8].'</b> no existe.';
                                    }

                                //}
                           }
                        }//fin if
                            $aux++;
                    }//fin while


                    foreach($arrayFacturas as $k => $facturas){

                        $remisiones =array_unique($facturas['remision']);

                        $estadoFactura = $emc->getRepository('FTPAdministradorBundle:EstadoFactura')->findOneBy(array('numeroFactura' => $k));

                        if($estadoFactura){
                            if($estadoFactura->getEstadoProcesamiento() == 0){
                                $estadoFactura->setNumeroFactura($k);
                                $estadoFactura->setCantidadRemisiones(count($remisiones));
                                $estadoFactura->setCantidadPedidos(count($remisiones));
                                //$estadoFactura->setFechaConfirmacion(new \DateTime());
                                $estadoFactura->setEstadoProcesamiento(0);

                                $emc->persist($estadoFactura);
                                $emc->flush();
                            }
                        }else{
                            $estadoFactura = new EstadoFactura();

                            $estadoFactura->setNumeroFactura($k);
                            $estadoFactura->setCantidadRemisiones(count($remisiones));
                            $estadoFactura->setCantidadPedidos(count($remisiones));
                            $estadoFactura->setFechaConfirmacion(new \DateTime());
                            $estadoFactura->setProveedor($emc->getReference('FTPAdministradorBundle:Proveedor',$idProveedorConvenios));
                            $estadoFactura->setEstadoProcesamiento(0);

                            $emc->persist($estadoFactura);
                            $emc->flush();
                        }
                        
                        $factura[]=$k;
                    }
         
            }
        }
        
        return $factura;
    }
    
    
    public function archivoPedidosConfirmados($numerosFactura, $idProveedorConvenios, $path, $dataClientes){
        
            set_time_limit(0);
            ini_set('memory_limit', '1024M');

            $ruta=$this->get('kernel')->getRootDir().'/../web/';
            
            $tarea=$this->container->get('generarArchivos');

            
            $em = $this->getDoctrine()->getManager('convenios');


            if(count($numerosFactura) > 0){
                /*$facturas = $request->get('facturas');
                $facturas = explode(",",$facturas);*/
                $detalleSinProcesar = $em->createQuery("SELECT p.id, p.numeroFactura, p.fechaFactura, ef.fechaConfirmacion, pr.codigoCopidrogas, p.consecutivo, p.remision, p.codigoDrogueria, pd.productoCodigo, pd.productoDescripcion,pd.cantidadPedida, pd.cantidadFinal, pd.productoPrecio, pd.productoIva, pd.productoCodigoBarras, pd.productoPresentacion, p.numeroCajas, pd.productoDescuento 
                    FROM FTPAdministradorBundle:Pedido p 
                    JOIN FTPAdministradorBundle:EstadoFactura ef WITH p.numeroFactura=ef.numeroFactura
                    JOIN FTPAdministradorBundle:PedidoDescripcion pd WITH pd.pedido=p.id 
                    JOIN FTPAdministradorBundle:Proveedor pr WITH p.proveedor = pr.id
                    WHERE ef.estadoProcesamiento=0 AND p.proveedor=".$idProveedorConvenios." AND p.numeroFactura IN ('".implode("','",$numerosFactura)."') ORDER BY p.fechaProcesado DESC ")->getResult();


                foreach($numerosFactura as $factura){
                    $estadoFactura= $em->getRepository('FTPAdministradorBundle:EstadoFactura')->findOneBy(array('numeroFactura' => $factura));
                    if($estadoFactura){
                        if($estadoFactura->getEstadoProcesamiento() == 0){
                            $estadoFactura->setEstadoProcesamiento(1);

                            $em->persist($estadoFactura);
                            $em->flush();
                        }

                        $updatePedidos=$em->createQuery("UPDATE FTPAdministradorBundle:Pedido p SET p.pedidoProcesado='1' WHERE p.pedidoProcesado=0 AND p.proveedor='".$idProveedorConvenios."' AND p.numeroFactura='".$factura."' ")->execute();

                    }
                }

            }

            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    ),
                    'bottom' => array(
                        'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    ),
                ),
            );

            $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject();
            $objPHPExcel->getProperties()->setCreator("liuggio");

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Pedidos_Confirmados');
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Calibri');
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);

            // Autosize
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);

            $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleArray);

            // Encabezados
            $row = 1;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Factura');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, 'Fecha Factura');
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, 'Nit');
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, 'Consecutivo SIP');
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, 'Numero Remision');
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, 'Codigo Drogueria');
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, 'Regional/Zona');
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, 'Codigo Producto');
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $row, 'Nombre Producto');
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $row, 'Cantidad Final');
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, 'Costo Unitario');
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $row, 'Bonificacion');
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $row, 'Porcentaje Iva');
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $row, 'Codigo Barras');
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $row, 'Presentacion');
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $row, 'Cajas');

            $row++;

            $consecutivo = 0;
            $activo = 1;
            $fondo = "";
            foreach ($detalleSinProcesar as $detalle) {

                if($detalle['consecutivo'] != $consecutivo){
                    if($activo == 1){
                        $fondo='C2F9FA';
                        $activo = 2; 
                    }else{
                        $fondo='FCEECD';
                        $activo = 1;                
                    }
                    $consecutivo = $detalle['consecutivo'];
                }else{
                    if($activo == 1){
                        $fondo='FCEECD';
                    }else{
                        $fondo='C2F9FA';
                    }
                }

                $estiloCelda = array('fill' => array('type' => \PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => $fondo)),'borders' => array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN,)));

                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':P'.$row)->applyFromArray($estiloCelda);


                if($detalle['cantidadFinal'] > 0){
                    if($detalle['cantidadFinal'] > $detalle['cantidadPedida']){
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getFont()->getColor()->setRGB("1907DF");
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getFont()->setBold(true);

                    }else if($detalle['cantidadFinal'] < $detalle['cantidadPedida']){
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getFont()->getColor()->setRGB("C00404");
                        $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getFont()->setBold(true);
                    }
                    $cantidadFinal=$detalle['cantidadFinal'];
                }else{
                    $cantidadFinal=$detalle['cantidadPedida'];
                }


                if($detalle['fechaConfirmacion']){
                    $fecha = $detalle['fechaConfirmacion']->format('Y-m-d H:i:s');
                }else{
                    if($detalle['fechaFactura']){
                        $fecha = $detalle['fechaFactura']->format('Y-m-d H:i:s');
                    }else{
                        $fecha = "";
                    }
                }

                $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $detalle['numeroFactura']);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $fecha);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $detalle['codigoCopidrogas']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $detalle['consecutivo']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $detalle['remision']);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $detalle['codigoDrogueria']);
                if(isset($dataClientes['centro'][$detalle['codigoDrogueria']])){
                   $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $dataClientes['centro'][$detalle['codigoDrogueria']]); 
                }else{
                   $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, ""); 
                }
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $detalle['productoCodigo']);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $detalle['productoDescripcion']);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $cantidadFinal);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $detalle['productoPrecio']);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $row, $detalle['productoDescuento']);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $row, $detalle['productoIva']); 
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $row, $detalle['productoCodigoBarras']);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $row, $detalle['productoPresentacion']);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $row, $detalle['numeroCajas']);

                $row++;
            }

            // Autosize rows
            $objPHPExcel->getActiveSheet()->getStyle('A1:P1000')->getAlignment()->setWrapText(true);
            foreach ($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) {
                $rd->setRowHeight(-1);
            }

            $nombreArchivo = 'Pedidos_Confirmados_'.$idProveedorConvenios.'_'.date('Ymd_H_m_s').'.xls';
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save($ruta.$nombreArchivo);
            
            $tarea->transaccionFTP($ruta.$nombreArchivo, $nombreArchivo,$path.'confirmados/');


            //return $response;

    }
    
    public function archivoPedidosPendientes($idProveedorConvenios, $idsPedidos, $path, $dataClientes){
        
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $ruta=$this->get('kernel')->getRootDir().'/../web/';

        $tarea=$this->container->get('generarArchivos');


        $em = $this->getDoctrine()->getManager('convenios');
        
        $pedidos = $em->createQuery("SELECT p FROM FTPAdministradorBundle:Pedido p
                                        LEFT JOIN p.transferencista t
                                        LEFT JOIN p.proveedor pro
                                        WHERE pro.id =:idProveedor AND p.id IN ('".implode("','",$idsPedidos)."') ")
                ->setParameter('idProveedor', $idProveedorConvenios)->getResult();
        
        $estado=array(0=>'Confirmado',1=>'Enviado Proveedor',2=>'Recibido Copidrogas',3=>'Enviado Asociado',99=>'Eliminado');


         if($idProveedorConvenios != null){
             
             $template= $this->renderView('FTPAdministradorBundle:Proveedor:pedidosPendientesCsv.html.twig',array('pedidos'=>$pedidos,'estado'=>$estado,'cliente'=>$dataClientes));
             
             $nombreArchivo="pedidos_pendientes_actualizacion_".date('Y-m-d_H_m_s').".csv";
             $archivo = fopen($ruta.$nombreArchivo,'w');
        
            fwrite ($archivo,$template);
            fclose ($archivo);

            $tarea->transaccionFTP($ruta.$nombreArchivo, $nombreArchivo,$path.'pendienteActualizar/');
                 
        }
    }
    
    public function validarFecha($fecha){
        $erUno="/(?<yyyy>\d{4})-(?<mm>\d{1,2})-(?<dd>\d{1,2})/";
        $erCuatro="/(?<yyyy>\d{4})\/(?<mm>\d{1,2})\/(?<dd>\d{1,2})/";
        if(preg_match($erUno, $fecha)||preg_match($erCuatro, $fecha)){
            return true;
        }else{
            return false;
        }
    }


    private function enviarCorreoProveedor($view){

        $message=new \Swift_Message();
        $message->setSubject('Ejecutada tarea programada de SipProveedores. ');
        $message->setFrom($this->container->getParameter('administrador_email'),$this->container->getParameter('administrador_nombre'));
        $message->setPriority(1);

        $em= $this->getDoctrine()->getManager();
        //$administradores=$em->getRepository('FTPAdministradorBundle:Administrador')->findBySeguimiento(1);
        
        //$message->setBcc(array('alejandro@waplicaciones.co'=>'Alejandro Ardila.',
         //   'j.casas@waplicaciones.co'=>'Julian Casas.'));

        $message->addBcc('j.casas@waplicaciones.co', 'Julian Casas.');

        $arrayAdministradores=array();
        
        /*foreach ($administradores as $administrador) {
             //if (!filter_var($administrador->getEmail(), FILTER_VALIDATE_EMAIL) === false ) 
                $arrayAdministradores[$administrador->getEmail()]=$administrador->getNombre();     
        }
        $message->setTo($arrayAdministradores);*/
        $message->addTo($this->container->getParameter('administrador_email'),$this->container->getParameter('administrador_nombre'));

        set_time_limit(120);
        $message->setBody($view,'text/html');

        try{
            $this->get('mailer')->send($message);
            return true;
        }catch(\Exception $e){
            $data['noEnviados'] = 1;
            return false;
        }
    }

}//fin class
