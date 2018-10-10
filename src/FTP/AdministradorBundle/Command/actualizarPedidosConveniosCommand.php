<?php
namespace FTP\AdministradorBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use FTP\AdministradorBundle\Entity\EstadoFactura;
use FTP\AdministradorBundle\Entity\Pedido;
use FTP\AdministradorBundle\Entity\PedidoDescripcion;

class actualizarPedidosConveniosCommand extends ContainerAwareCommand{

    private $message='';

    protected function configure(){
        parent::configure();
        $this->setName('actualizarPedidos:convenios')->setDescription('Actualiza los pedidos para convenios');
    }
    protected function execute(InputInterface $input, OutputInterface $output){
        exit('mantenimiento actualizarPedidos:convenios');
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $bandera=true;

        $output->writeln("Iniciando...");
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        $emc = $this->getContainer()->get('doctrine')->getManager('convenios');
        $ems = $this->getContainer()->get('doctrine')->getManager('sipasociados');
        

        //Variables de entorno.
        $informe=array();

        $dir = $this->getContainer()->get('kernel')->getRootDir().'/../web/';
        $resultadoFinal='';
        $asociados=array();

        //servicios
        $tarea=$this->getContainer()->get('generarArchivos');

        $output->writeln("Cargando Proveedores...");
        //Recuperar proveedores disponibles.
        $output->writeln("Consultando proveedores disponibles. ");
        $this->registralog("Consultando proveedores disponibles. ");
        $proveedores=$em->createQueryBuilder()
        ->select('p.id, p.proveedor,p.nitProveedor as nit,p.estadoConvenios, p.carpetaConvenios')
        ->from('FTPAdministradorBundle:Proveedores','p')
        ->where('p.estadoConvenios=1')
        //->where('p.codigoProveedor=:codigo')
        //->setParameter(':codigo','A46')
        ->getQuery()->getResult();

        $output->writeln("Consultando proveedores disponibles CONVENIOS. ");
        $this->registralog("Consultando proveedores disponibles CONVENIOS. ");

        $proveedoresConvenios = $emc->getRepository('FTPAdministradorBundle:Proveedor')->findAll();
        $arrayProveedoresConvenios = array();
        foreach($proveedoresConvenios as $datosProveedor){
            $arrayProveedoresConvenios[$datosProveedor->getCodigoCopidrogas()] = $datosProveedor->getId();
        }

        $output->writeln("Consultando Droguerias de Asiciados  ");
        $this->registralog("Consultando Droguerias de Asiciados  ");

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


        /****************BUSQUEDA DE FACTURAS REGISTRADAS********************/
        $facturasConfirmadas = array();
        $output->writeln("Consultando Facturas registradas ");
        $facturasRegistradas = $emc->createQuery("SELECT ef.numeroFactura, p.id AS proveedor, ef.estadoProcesamiento FROM FTPAdministradorBundle:EstadoFactura ef JOIN FTPAdministradorBundle:Proveedor p WITH ef.proveedor = p.id  ")->getResult();
        foreach($facturasRegistradas as $facturaRegistrada){
            $facturasConfirmadas[$facturaRegistrada['numeroFactura']]['factura'] = $facturaRegistrada['numeroFactura'];
            $facturasConfirmadas[$facturaRegistrada['numeroFactura']]['proveedor'] = $facturaRegistrada['proveedor'];
            $facturasConfirmadas[$facturaRegistrada['numeroFactura']]['estado'] = $facturaRegistrada['estadoProcesamiento'];
        }
        /**********************************/



        //Generar pedido.
        if($bandera){
            $bandera=false;

            foreach($proveedores as $proveedor){
                $output->writeln("Actualizando pedidos. proveedor NIT: ".$proveedor['nit']);
                $this->registralog("Actualizando pedidos. proveedor NIT: ".$proveedor['nit']);
                //$informe[$proveedor['nit']]=$this->procesarPedidoMultipleAction($proveedor,$proveedor['carpetaConvenios']."/", $output, $arrayProveedoresConvenios,$dataClientes,$facturasConfirmadas); //Recuperar CSV y generar pedido en Convenios.
                
                $this->procesarArchivosActualizacion($proveedor['carpetaConvenios']."/",$facturasConfirmadas,$proveedor,$arrayProveedoresConvenios,$dataClientes,$output);
            }
        }

        // private function enviarCorreo(){

        $resultado['app']='convenios';
        $resultado['informe'] =$resultadoFinal;


        
        $output->writeln("Fin de la tarea.");
    }
    
    public function estadoConvenios($proveedores){
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
    
    
    public function procesarArchivosActualizacion($path, $facturasConfirmadas,$proveedor,$arrayProveedoresFtp,$dataClientes,$output){
        
        //conxiones.
        $em=$this->getContainer()->get('doctrine')->getManager();
        $emc=$this->getContainer()->get('doctrine')->getManager('convenios');
        $ems = $this->getContainer()->get('doctrine')->getManager('sipasociados');
        $dir = $this->getContainer()->get('kernel')->getRootDir().'/../web/';

        $tarea2=$this->getContainer()->get('generarArchivos');
        
        $arrayArchivosAProcesar = array();
        $logRespuesta=array();
        $informe=array();
        
        $output->writeln("Se incia la tarea de actualizacion de pedidos convenios");
        $this->registralog("Se incia la tarea de actualizacion de pedidos convenios");
        
        //SE PONE EN COMENTARIO MIENTRAS PRUEBAS LOCALES
        $conexionFTP=@ftp_connect($this->getContainer()->getParameter('host_ftp'),$this->getContainer()->getParameter('port'));
        @ftp_login($conexionFTP,$this->getContainer()->getParameter('user_ftp'),$this->getContainer()->getParameter('pass_ftp'));
        
        if(!@ftp_chdir($conexionFTP,$path.'pendienteActualizar/')){
            $directorio = false;
            echo "no entra";
        }else{
            if(@ftp_chdir($conexionFTP,$path.'pendienteActualizar/') == false){
                //@ftp_mkdir($conexionFTP,'pendienteActualizar');
                @ftp_chdir($conexionFTP,$path.'pendienteActualizar/');
                echo "crea directorio";
                $directorio = true;
            }else{
                $directorio = true;
                echo "directorio existe";
            }
        }
        echo ftp_pwd($conexionFTP);
        //BANDERA DE PREUBA LOCAL
        //$directorio = true;
        if($directorio){
            
            //SE PONE EN COMENTARIO MIENTRAS PRUEBAS LOCALES
            $documentos = ftp_nlist($conexionFTP, ".");
            //dump($documentos);exit();
            
            $output->writeln("Se buscan archivos a procesar para actualizar");
            $this->registralog("Se buscan archivos a procesar para actualizar");

            foreach ($documentos as $file) {
                //$output->writeln("Se valida el archivo ".$file);
                if(substr($file, -3)=='csv'||substr($file, -3)=='CSV' ){
                    $arrayArchivosAProcesar[]=$file;

                    //@ftp_get($conexionFTP,$dir."convenios/vogue/".$file,$file, FTP_BINARY);
                }
            }
            unset($documentos);
            
            //$arrayArchivosAProcesar[]="prueba_actualizar_detalle.csv";
            //$arrayArchivosAProcesar[]="Dspcho20170131181509-L10095981835.csv";

            if(count($arrayArchivosAProcesar) > 0){
                $arrayFacturas=array();
                $contador = 1;
                foreach($arrayArchivosAProcesar as $archivo){

                    if($contador <= 65){

                    
                        $logRespuesta=array();
                        $output->writeln("Se incia el procesamiento del archivo ".$archivo);
                        $this->registralog("Se incia el procesamiento del archivo ".$archivo);
                        
                        $productosArchivo = array();
                        $bonificacionArchivo = array();
                        $pedidosActualizarFacturas = array();
                        
                        //SE PONE EN COMENTARIO MIENTRAS PRUEBAS LOCALES
                        @ftp_get($conexionFTP,$dir.$archivo,$archivo, FTP_BINARY);
                        @ftp_delete($conexionFTP,$archivo);
                        if($fp = @fopen($dir.$archivo, "rb")){
                            if (count(fgetcsv($fp, 1000, ";")) > 2) {
                                fseek($fp, 0);
                                while($datos = fgetcsv($fp, 1000, ";")){
                                    
                                    if(isset($datos[0]) && isset($datos[1]) && isset($datos[4])){
                                        
                                        $datos[0] = trim($datos[0]);//datos[0] = consecutivo
                                        $datos[1] = trim($datos[1]);//datos[1] = codigo
                                        $datos[2] = trim($datos[2]);//datos[2] = producto
                                        $datos[3] = trim($datos[3]);//datos[3] = codigo de barras
                                        $datos[4] = trim($datos[4]);//datos[4] = cantidadFinal
                                        $datos[5] = trim($datos[5]);//datos[5] = precio
                                        $datos[6] = trim($datos[6]);//datos[6] = bonificacion
                                        $datos[7] = trim($datos[7]);//datos[7] = #cajas
                                        $datos[8] = trim($datos[8]);//datos[8] = remision
                                        $datos[9] = trim($datos[9]);//datos[9] = factura
                                        
                                        if( ($datos[0]!='') && ( is_numeric($datos[4]) && ( strlen($datos[4])<=3 )) && ($datos[1]!='')){

                                            $estructuraPedido = explode("-", $datos[0]);

                                            if(count($estructuraPedido) == 3){

                                                //$output->writeln("se lee el producto ".$datos[1]);
                                                if(isset($datos[6]) && $datos[6] !="" && $datos[6] !=0){
                                                    if($datos[5] == 0){//bonificacion en especie
                                                        $output->writeln("producto ".$datos[1]." bonificado en especie");
                                                        //$productosArchivo[$datos[0]][$datos[1]] = array(
                                                        $productosArchivo[$datos[0]][] = array(
                                                            'cantidad' => $datos[4], 
                                                            'producto' => $datos[1],
                                                            'productoModificado' => str_pad((string)$datos[1], 9, "0", STR_PAD_LEFT),
                                                            'precio' => $datos[5],
                                                            'bonificado' => 1,
                                                            'tipo' => 1);
                                                           
                                                    }else if($datos[5] > 0){//bonificacion en precio
                                                        //$output->writeln("producto ".$datos[1]." bonificado en precio");
                                                        //$productosArchivo[$datos[0]][$datos[1]] = array(
                                                        $productosArchivo[$datos[0]][] = array(
                                                            'cantidad' => $datos[4], 
                                                            'producto' => $datos[1],
                                                            'productoModificado' => str_pad((string)$datos[1], 9, "0", STR_PAD_LEFT),
                                                            'precio' => $datos[5],
                                                            'bonificado' => 1,
                                                            'tipo' => 2);
                                                    }
                                                }else{
                                                    //$output->writeln("producto ".$datos[1]." para actualizar");
                                                    //$productosArchivo[$datos[0]][$datos[1]] = array(
                                                    $productosArchivo[$datos[0]][] = array(
                                                        'cantidad' => $datos[4], 
                                                        'producto' => $datos[1],
                                                        'productoModificado' => str_pad((string)$datos[1], 9, "0", STR_PAD_LEFT),
                                                        'precio' => $datos[5],
                                                        'bonificado' => 0,
                                                        'tipo' => 0);
                                                }
                                                
                                                
                                                if($datos[7] !='' && $datos[8] !='' && $datos[9] !='' ){

                                                    $pedidosActualizarFacturas[$datos[0]]['cajas'] = $datos[7];
                                                    $pedidosActualizarFacturas[$datos[0]]['remision'] = $datos[8];
                                                    $pedidosActualizarFacturas[$datos[0]]['factura'] = $datos[9];

                                                    $output->writeln("se almacena la factura ".$datos[9]);
                                                }else{
                                                    //$output->writeln("NO SE ALMACENA la factura".$datos[9]);
                                                }

                                            }else{
                                                $output->writeln("estructura incorrecta");
                                                $logRespuesta[]= "estructura incorrecta en el consecutivo del pedido [".$datos[0]."]";
                                            }
                                            
                                            
                                        }else{
                                            $output->writeln("estructura incorrecta");
                                            $logRespuesta[]= "estructura incorrecta del archivo ".$archivo;
                                        }
                                    }else{
                                        $output->writeln("estructura incompleta");
                                        $logRespuesta[]= "estructura incompleta ".$archivo;
                                    }
                                }//fin while
                                //exit();
                                //var_dump($productosArchivo);exit();
                                foreach($productosArchivo as $consecutivo => $productos){
                                    $datosPedido = explode("-",$consecutivo);
                                    $idPedido = $datosPedido[0];
                                    
                                    //se actualia la cantidad final de los productos del pedido
                                    foreach($productos as $codProducto => $producto){
                                        
                                        //$output->writeln("se procesa el producto ".$codProducto);
                                        $output->writeln("se procesa el producto ".$producto['producto']);
                                        //se valida si el producto es una bonificacion
                                        if($producto['bonificado'] == 1){
                                            
                                            
                                            if($producto['tipo'] == 1){//bonificacion en especie
                                                
                                                //$output->writeln("producto bonificado en especie ".$codProducto);
                                                //$this->registralog("producto bonificado en especie ".$codProducto);
                                                $output->writeln("producto bonificado en especie ".$producto['producto']);
                                                $this->registralog("producto bonificado en especie ".$producto['producto']);

                                                
                                                $datosPedido = $emc->getRepository('FTPAdministradorBundle:Pedido')->findOneByConsecutivo($consecutivo);


                                                //$productoInfo = $emc->getRepository('FTPAdministradorBundle:PedidoDescripcion')->findOneBy(array('pedido' => $idPedido, 'productoCodigo' => $producto['producto'], 'bonificacion' => 1));

                                                $productoQuery = $emc->createQuery("SELECT dp.id FROM FTPAdministradorBundle:PedidoDescripcion dp 
                                                    WHERE dp.pedido=".$idPedido." AND (dp.productoCodigo ='".$producto['producto']."' OR dp.productoCodigo ='".$producto['productoModificado']."' ) AND dp.bonificacion = 1 ");
                                                $productoDat = $productoQuery->getResult();

                                                if($productoDat){
                                                //if($productoInfo){


                                                    //$productosPedido = $emc->createQuery("UPDATE FTPAdministradorBundle:PedidoDescripcion dp SET dp.cantidadFinal='".$producto['cantidad']."' WHERE dp.productoCodigo='".$producto['producto']."' AND dp.pedido=".$idPedido." AND dp.bonificacion=1 ")->getResult();

                                                    $productosPedido = $emc->createQuery("UPDATE FTPAdministradorBundle:PedidoDescripcion dp SET dp.cantidadFinal='".$producto['cantidad']."' WHERE dp.pedido=".$idPedido." AND (dp.productoCodigo='".$producto['producto']."' OR dp.productoCodigo ='".$producto['productoModificado']."') AND  dp.bonificacion=1 ")->getResult();




                                               }else{

                                                    //$datosProducto = $emc->getRepository('FTPAdministradorBundle:InventarioProducto')->findOneBy(array('codigo' => $producto['producto'], 'proveedor' => $arrayProveedoresFtp[$proveedor['nit']]));

                                                    $datosProducto = $emc->getRepository('FTPAdministradorBundle:InventarioProducto')->findOneBy(array('codigo' => $producto['productoModificado'], 'proveedor' => $arrayProveedoresFtp[$proveedor['nit']]));

                                                    if($datosPedido && $datosProducto){
                                                        $this->insertarProductoBonificado($datosProducto, $datosPedido, $idPedido, $producto['cantidad']);
                                                        

                                                        $output->writeln("se inserta producto bonificado en especie ".$producto['producto']);
                                                        $this->registralog("Pedido [".$consecutivo."].se isnerta producto bonificado en especie ".$producto['producto']);


                                                        $logRespuesta[]= "Pedido [".$consecutivo."].se inserta producto bonificado en especie ".$producto['producto'];
                                                    }else{

                                                        $output->writeln("No es posible insertar la bonificacion.Producto no encontrado en el inventario ");
                                                        $this->registralog("Pedido [".$consecutivo."].No es posible insertar la bonificacion.Producto no encontrado en el inventario ");


                                                        $logRespuesta[]= "Pedido [".$consecutivo."].No es posible insertar la bonificacion.Producto [".$producto['producto']."] no encontrado en el inventario ";
                                                    }

                                               }

                                                
                                                
                                            }else if($producto['tipo'] == 2){//bonificacion en precio

                                                //$productoInfo = $emc->getRepository('FTPAdministradorBundle:PedidoDescripcion')->findOneBy(array('pedido' => $idPedido, 'productoCodigo' => $producto['producto'], 'bonificacion' => 1));

                                                $productoQuery = $emc->createQuery("SELECT dp.id FROM FTPAdministradorBundle:PedidoDescripcion dp 
                                                    WHERE dp.pedido=".$idPedido." AND (dp.productoCodigo ='".$producto['producto']."' OR dp.productoCodigo ='".$producto['productoModificado']."' ) AND dp.bonificacion = 1 ");
                                                $productoDat = $productoQuery->getResult();

                                                if($productoDat){
                                                    $productoDat = $productoDat[0];

                                                    $productoInfo = $emc->getRepository('FTPAdministradorBundle:PedidoDescripcion')->findOneById($productoDat['id']);
                                                //if($productoInfo){
                                                    if($producto['cantidad'] > $productoInfo->getCantidadPedida()){

                                                    }else{

                                                        //$productosPedido = $emc->createQuery("UPDATE FTPAdministradorBundle:PedidoDescripcion dp SET dp.cantidadFinal='".$producto['cantidad']."', dp.productoPrecio='".$producto['precio']."', dp.bonificacion='1' WHERE dp.productoCodigo='".$producto['producto']."' AND dp.pedido=".$idPedido)->getResult();

                                                        $productosPedido = $emc->createQuery("UPDATE FTPAdministradorBundle:PedidoDescripcion dp SET dp.cantidadFinal='".$producto['cantidad']."', dp.productoPrecio='".$producto['precio']."', dp.bonificacion='1' WHERE dp.pedido=".$idPedido." AND ( dp.productoCodigo='".$producto['producto']."' OR dp.productoCodigo ='".$producto['productoModificado']."' ) ")->getResult();
                                                

                                                        $output->writeln("se actualiza producto bonificado en precio ".$producto['producto']);
                                                        $this->registralog("Pedido [".$consecutivo."].se actualiza producto bonificado en precio ".$producto['producto']);
                                                        $logRespuesta[]= "Pedido [".$consecutivo."].se actualiza producto bonificado en precio ".$producto['producto'];
                                                    }
                                                }
                                                
                                                
                                                
                                            }
                                            
                                        }else{

                                            
                                            //$productoInfo = $emc->getRepository('FTPAdministradorBundle:PedidoDescripcion')->findOneBy(array('pedido' => $idPedido, 'productoCodigo' => $producto['producto'], 'bonificacion' => NULL));

                                            $productoQuery = $emc->createQuery("SELECT dp.id FROM FTPAdministradorBundle:PedidoDescripcion dp 
                                                WHERE dp.pedido=".$idPedido." AND (dp.productoCodigo ='".$producto['producto']."' OR dp.productoCodigo ='".$producto['productoModificado']."' ) AND dp.bonificacion IS NULL ");
                                            $productoDat = $productoQuery->getResult();

                                            if($productoDat){
                                                $productoDat = $productoDat[0];

                                                $productoInfo = $emc->getRepository('FTPAdministradorBundle:PedidoDescripcion')->findOneById($productoDat['id']);

                                                if($producto['cantidad'] > $productoInfo->getCantidadPedida()){

                                                    $output->writeln("Pedido [".$consecutivo."]. No es posible ingresar cantidades superiores a la insertada originalmente en el Producto ".$producto['producto']);

                                                    $this->registralog("Pedido [".$consecutivo."]. No es posible ingresar cantidades superiores[".$producto['cantidad']."] a la insertada originalmente[".$productoInfo->getCantidadPedida()."]  Producto ".$producto['producto']);


                                                    $logRespuesta[]= "Pedido [".$consecutivo."]. No es posible ingresar cantidades superiores[".$producto['cantidad']."] a la original[".$productoInfo->getCantidadPedida()."] Producto ".$producto['producto'];

                                                }else{


                                                    $productosPedido = $emc->createQuery("UPDATE FTPAdministradorBundle:PedidoDescripcion dp SET dp.cantidadFinal='".$producto['cantidad']."' WHERE dp.pedido=".$idPedido." AND (dp.productoCodigo='".$producto['producto']."' OR dp.productoCodigo='".$producto['productoModificado']."' ) AND  dp.bonificacion IS NULL ")->getResult();
                                                
                                                    //$output->writeln("se actualiza cantidad final producto ".$codProducto);
                                                    //$this->registralog("Pedido [".$consecutivo."].se actualiza cantidad final [".$producto['cantidad']."] producto ".$codProducto);
                                                    //$logRespuesta[]= "Pedido [".$consecutivo."].Se actualiza cantidad final[".$producto['cantidad']."] producto ".$codProducto;
                                                    $output->writeln("se actualiza cantidad final producto ".$producto['producto']);
                                                    $this->registralog("Pedido [".$consecutivo."].se actualiza cantidad final [".$producto['cantidad']."] producto ".$producto['producto']);
                                                    $logRespuesta[]= "Pedido [".$consecutivo."].Se actualiza cantidad final[".$producto['cantidad']."] producto ".$producto['producto'];
                                                }
                                            }else{

                                                $output->writeln("Pedido [".$consecutivo."]. Producto NO encnotrado ".$producto['producto']);

                                                $this->registralog("Pedido [".$consecutivo."]. Producto NO encnotrado ".$producto['producto']);

                                                $logRespuesta[]= "Pedido [".$consecutivo."]. Producto NO encnotrado ".$producto['producto'];
                                            }

                                            
                                            
                                            
                                        }
                                    }
                                    
                                    $this->actualizacionTotalPedido($idPedido);
                                    
                                    $output->writeln("se actualiza el total del pedido".$idPedido);
                                    $this->registralog("se actualiza el total del pedido ".$idPedido);
                                    
                                    //se actualiza la factura y remision del pedido
                                    if(isset($pedidosActualizarFacturas[$consecutivo])){

                                        dump($pedidosActualizarFacturas[$consecutivo]);
                                        
                                        $facturaAActualizar = $pedidosActualizarFacturas[$consecutivo]['factura'];
                                        if( isset($facturasConfirmadas[$facturaAActualizar])){


                                                $infoPedido = $emc->getRepository('FTPAdministradorBundle:Pedido')->findOneBy(array('consecutivo' =>$consecutivo));
                                                if($infoPedido){
                                                    if($pedidosActualizarFacturas[$consecutivo]['factura'] !=0){
                                                        $infoPedido->setNumeroFactura($pedidosActualizarFacturas[$consecutivo]['factura']);
                                                        $infoPedido->setNumeroCajas($pedidosActualizarFacturas[$consecutivo]['cajas']);
                                                        $infoPedido->setFechaProcesado(new \DateTime());

                                                        if($infoPedido->getRemision() ){
                                                        }else{
                                                            $infoPedido->setRemision($pedidosActualizarFacturas[$consecutivo]['remision']);
                                                        }

                                                        $emc->persist($infoPedido);
                                                        $emc->flush();

                                        
                                                        $arrayFacturas[$pedidosActualizarFacturas[$consecutivo]['factura']]['remision'][] = $pedidosActualizarFacturas[$consecutivo]['remision'];
                                                
                                                        $output->writeln("se actualiza la factura remision y cajas para el pedido ".$idPedido);
                                                        $this->registralog("se actualiza la factura remision y cajas para el pedido ".$idPedido);

                                                    }
                                                    
                                                }
                                            /***EN COMENTARIO TEMPORALMENTE MIENTRAS ACTUALIZAN LOS PEDIDOS PENDIENTES**/
                                            /*if($facturasConfirmadas[$facturaAActualizar]['estado'] == 0){
                                                
                                                $infoPedido = $emc->getRepository('FTPAdministradorBundle:Pedido')->findOneBy(array('consecutivo' =>$consecutivo));
                                                if($infoPedido){
                                                    if($pedidosActualizarFacturas[$consecutivo]['factura'] !=0){
                                                        $infoPedido->setNumeroFactura($pedidosActualizarFacturas[$consecutivo]['factura']);
                                                        $infoPedido->setNumeroCajas($pedidosActualizarFacturas[$consecutivo]['cajas']);
                                                        $infoPedido->setFechaProcesado(new \DateTime());

                                                        if($infoPedido->getRemision() ){
                                                        }else{
                                                            $infoPedido->setRemision($pedidosActualizarFacturas[$consecutivo]['remision']);
                                                        }

                                                        $emc->persist($infoPedido);
                                                        $emc->flush();

                                        
                                                        $arrayFacturas[$pedidosActualizarFacturas[$consecutivo]['factura']]['remision'][] = $pedidosActualizarFacturas[$consecutivo]['remision'];
                                                
                                                        $output->writeln("se actualiza la factura remision y cajas para el pedido ".$idPedido);
                                                        $this->registralog("se actualiza la factura remision y cajas para el pedido ".$idPedido);

                                                    }
                                                    
                                                }
                                                
                                            }else{
                                                
                                                $output->writeln("No se actualiza la factura ".$pedidosActualizarFacturas[$consecutivo]['factura']." para el pedido ".$consecutivo." debido a que fue registrada y confirmada previamente");
                                                
                                                $this->registralog("No se actualiza la factura ".$pedidosActualizarFacturas[$consecutivo]['factura']." para el pedido ".$consecutivo." debido a que fue registrada y confirmada previamente");

                                                $logRespuesta[]= "No se actualiza la factura ".$pedidosActualizarFacturas[$consecutivo]['factura']." para el pedido ".$consecutivo." debido a que fue registrada y confirmada previamente";
                                            }*/
                                        }else{

                                            $infoPedido = $emc->getRepository('FTPAdministradorBundle:Pedido')->findOneBy(array('consecutivo' =>$consecutivo));
                                            if($infoPedido){
                                                $infoPedido->setNumeroFactura($pedidosActualizarFacturas[$consecutivo]['factura']);
                                                $infoPedido->setNumeroCajas($pedidosActualizarFacturas[$consecutivo]['cajas']);
                                                $infoPedido->setFechaProcesado(new \DateTime());

                                                if($infoPedido->getRemision() ){
                                                }else{
                                                    $infoPedido->setRemision($pedidosActualizarFacturas[$consecutivo]['remision']);
                                                }

                                                $emc->persist($infoPedido);
                                                $emc->flush();

                                        
                                                $arrayFacturas[$pedidosActualizarFacturas[$consecutivo]['factura']]['remision'][] = $pedidosActualizarFacturas[$consecutivo]['remision'];
                                                
                                                $output->writeln("se actualiza la factura remision y cajas para el pedido ".$idPedido);
                                                $this->registralog("se actualiza la factura remision y cajas para el pedido ".$idPedido);

                                            }
                                            
                                            
                                        
                                        }
      
                                    }
                                    
                                }
                                
                                
                                
                                
                            }
                            
                            fclose($fp);


                            $txt[]=$tarea2->generarLog($informe,$path, $this->getContainer()->get('kernel')->getRootDir().'/../web/', $logRespuesta, $archivo);
                        }
                        @unlink($dir.$archivo);
                    }
                    $contador++;
                }//fin foreach
                
                /*******INICIA EL PROCESO DE CONFIRMACION DE FACTURAS*********/
                $output->writeln("se inicia el proceso de confirmacion de facturas ");
                $factura = array();
                dump($arrayFacturas);
                foreach($arrayFacturas as $k => $facturas){

                    //if($k != 0){
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

                                $this->registralog("se actualiza la factura ".$k." de estado_factura ");
                            }
                        }else{
                            $estadoFactura = new EstadoFactura();

                            $estadoFactura->setNumeroFactura($k);
                            $estadoFactura->setCantidadRemisiones(count($remisiones));
                            $estadoFactura->setCantidadPedidos(count($remisiones));
                            $estadoFactura->setFechaConfirmacion(new \DateTime());
                            $estadoFactura->setProveedor($emc->getReference('FTPAdministradorBundle:Proveedor',$arrayProveedoresFtp[$proveedor['nit']]));
                            $estadoFactura->setEstadoProcesamiento(0);

                            $emc->persist($estadoFactura);
                            $emc->flush();

                            $this->registralog("se crea la factura ".$k." de estado_factura ");
                        }

                        $factura[]=$k;
                        //$output->writeln("Se registra la factura ".$k);
                    //}
                    
                }

                /**************************************************/
                
                /*************************CREACION DEL ARCHIVO DE PEDIDOS CONFIRMADOS****************************/
                if(count($factura) > 0){
                    $this->archivoPedidosConfirmados($factura, $arrayProveedoresFtp[$proveedor['nit']], $path, $dataClientes, $output);
                }
                /***************************************************************************************************/
            }
            
        }else{
            $output->writeln("El proveedor [".$proveedor['nit']."] no tiene asignada la carpeta raiz");
            $this->registralog("El proveedor [".$proveedor['nit']."]  no tiene asignada la carpeta raiz");
        }
        
        
        
    }
    
    
    public function insertarProductoBonificado($datosProducto, $datosPedido, $idPedido, $cantidad){
        
        $emc=$this->getContainer()->get('doctrine')->getManager('convenios');
        
        $pedidoDescripcion = new PedidoDescripcion();
        $pedidoDescripcion->setPdtoId($datosProducto->getId());
        $pedidoDescripcion->setPedido($emc->getReference('FTPAdministradorBundle:Pedido', $idPedido));
        $pedidoDescripcion->setDrogueriaId($datosPedido->getCodigoDrogueria());
        //$pedidoDescripcion->setTransferencista($emc->getReference('FTPAdministradorBundle:Transferencista',$datosPedido->getTransferencista()->getId()));
        if($datosPedido->getTransferencista()){
            $pedidoDescripcion->setTransferencista($emc->getReference('FTPAdministradorBundle:Transferencista',$datosPedido->getTransferencista()->getId()));
        }
        $pedidoDescripcion->setCantidadPedida($cantidad);
        $pedidoDescripcion->setProductoCodigo($datosProducto->getCodigo());
        $pedidoDescripcion->setProductoCodigoBarras($datosProducto->getCodigoBarras());
        $pedidoDescripcion->setProductoDescripcion($datosProducto->getDescripcion());
        $pedidoDescripcion->setProductoPresentacion($datosProducto->getPresentacion());
        $pedidoDescripcion->setProductoPrecio(0);
        $pedidoDescripcion->setProductoMarcado(0);
        $pedidoDescripcion->setProductoReal($datosProducto->getPrecio());
        $pedidoDescripcion->setProductoIva($datosProducto->getIva());
        $pedidoDescripcion->setLinea($emc->getReference('FTPAdministradorBundle:Linea',$datosProducto->getLinea()->getId()));
        $pedidoDescripcion->setProductoDescuento($datosProducto->getDescuento());
        $pedidoDescripcion->setProductoFecha(new \DateTime());
        $pedidoDescripcion->setProductoEstado(1);
        $pedidoDescripcion->setCantidadFinal($cantidad);
        $pedidoDescripcion->setProductoFoto($datosProducto->getFoto());
        $pedidoDescripcion->setBonificacion(1);
        
        $emc->persist($pedidoDescripcion);
        $emc->flush();    
        
    }
    
    
    public function actualizacionTotalPedido ($idPedido){
        
        $emc=$this->getContainer()->get('doctrine')->getManager('convenios');
        
        $detallePedido = $emc->createQuery('SELECT count(pd.cantidadFinal) AS pedidos, SUM(
              (pd.productoPrecio * pd.cantidadFinal) - 
              ((pd.productoPrecio * pd.cantidadFinal) * pd.productoDescuento/100 ) +
              (   ( ( (pd.productoPrecio * pd.cantidadFinal) - ((pd.productoPrecio * pd.cantidadFinal) * pd.productoDescuento/100 ) )* pd.productoIva)/100   )
              ) AS total
                  FROM FTPAdministradorBundle:PedidoDescripcion pd 
                  WHERE pd.pedido ='.$idPedido)->getSingleResult();

        $totalProductos=($detallePedido['pedidos']=="")?0:$detallePedido['pedidos'];
        
        $actualizarPedido = $emc->createQuery('UPDATE FTPAdministradorBundle:Pedido p SET p.totalPesos=:totalPesos, p.numeroProductos=:numProductos WHERE p.id=:idPedido ')
                ->setParameter('totalPesos', $detallePedido['total'])
                ->setParameter('numProductos', $totalProductos)
                ->setParameter('idPedido', $idPedido)
                ->execute();
        
    }


    public function archivoPedidosConfirmados($numerosFactura, $idProveedorConvenios, $path, $dataClientes, $output){
        
            set_time_limit(0);
            ini_set('memory_limit', '1024M');

            $ruta=$this->getContainer()->get('kernel')->getRootDir().'/../web/';
            
            $tarea=$this->getContainer()->get('generarArchivos');

            
            $em = $this->getContainer()->get('doctrine')->getManager('convenios');

            $facturaAProcesar = array();
            if(count($numerosFactura) > 0){

                foreach($numerosFactura as $fac){

                    $cajaVacia=0;
                    $remisionVacia=0;
                    $pedidos = $em->getRepository("FTPAdministradorBundle:Pedido")->findBy(array('numeroFactura' => $fac));
                    foreach($pedidos as $pedido){
                        if($pedido->getRemision() == ""){
                            $remisionVacia++;
                        }
                        if($pedido->getNumeroCajas() == ""){
                            $cajaVacia++;
                        }
                    }

                    if($cajaVacia == 0 && $remisionVacia == 0){
                        $facturaAProcesar[]=$fac;
                    }
                }
                
                $output->writeln("cantidad de facturas para los pedidos en el archivo = ".count($facturaAProcesar));
                /*$facturas = $request->get('facturas');
                $facturas = explode(",",$facturas);*/
                $detalleSinProcesar = $em->createQuery("SELECT p.id, p.numeroFactura, p.fechaFactura, ef.fechaConfirmacion, pr.codigoCopidrogas, p.consecutivo, p.remision, p.codigoDrogueria, pd.productoCodigo, pd.productoDescripcion,pd.cantidadPedida, pd.cantidadFinal, pd.productoPrecio, pd.productoIva, pd.productoCodigoBarras, pd.productoPresentacion, p.numeroCajas, pd.productoDescuento 
                    FROM FTPAdministradorBundle:Pedido p 
                    JOIN FTPAdministradorBundle:EstadoFactura ef WITH p.numeroFactura=ef.numeroFactura
                    JOIN FTPAdministradorBundle:PedidoDescripcion pd WITH pd.pedido=p.id 
                    JOIN FTPAdministradorBundle:Proveedor pr WITH p.proveedor = pr.id
                    WHERE ef.estadoProcesamiento=0 AND p.proveedor=".$idProveedorConvenios." AND p.numeroFactura IN ('".implode("','",$facturaAProcesar)."') ORDER BY p.fechaProcesado DESC ")->getResult();


                foreach($facturaAProcesar as $factura){
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

            $objPHPExcel = $this->getContainer()->get('phpexcel')->createPHPExcelObject();
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
            $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);

            $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArray);

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
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $row, 'Costo sin Descuento');

            $row++;

            $consecutivo = 0;
            $activo = 1;
            $fondo = "";
            
            $output->writeln("Se intena crear el archivo xls ");
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

                $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':Q'.$row)->applyFromArray($estiloCelda);

                if($detalle['cantidadFinal'] > $detalle['cantidadPedida']){
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getFont()->getColor()->setRGB("1907DF");
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getFont()->setBold(true);
                    
                }else if($detalle['cantidadFinal'] < $detalle['cantidadPedida']){
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getFont()->getColor()->setRGB("C00404");
                    $objPHPExcel->getActiveSheet()->getStyle('J'.$row)->getFont()->setBold(true);
                }
                $cantidadFinal=$detalle['cantidadFinal'];
                /*if($detalle['cantidadFinal'] > 0){
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
                }*/


                if($detalle['fechaConfirmacion']){
                    $fecha = $detalle['fechaConfirmacion']->format('Y-m-d H:i:s');
                }else{
                    if($detalle['fechaFactura']){
                        $fecha = $detalle['fechaFactura']->format('Y-m-d H:i:s');
                    }else{
                        $fecha = "";
                    }
                }

                if($detalle['productoDescuento'] > 0 ){
                    $costoProducto = $detalle['productoPrecio']  - ($detalle['productoPrecio']*($detalle['productoDescuento']/100));
                }else{
                    $costoProducto = $detalle['productoPrecio'];
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
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $costoProducto);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $row, $detalle['productoDescuento']);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $row, $detalle['productoIva']); 
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $row, $detalle['productoCodigoBarras']);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $row, $detalle['productoPresentacion']);
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $row, $detalle['numeroCajas']);
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $row, $detalle['productoPrecio']);

                $row++;
            }

            // Autosize rows
            $objPHPExcel->getActiveSheet()->getStyle('A1:Q1000')->getAlignment()->setWrapText(true);
            foreach ($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) {
                $rd->setRowHeight(-1);
            }

            $nombreArchivo = 'Pedidos_Confirmados_'.$idProveedorConvenios.'_'.date('Ymd_H_m_s').'.xls';
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save($ruta.$nombreArchivo);
            
            $this->registralog("4.3.2 Se genera la plantilla XLS [".$nombreArchivo."] con la informacion de los pedidos confirmados");
            
            //SE PONE EN COMENTARIO MIENTRAS PRUEBAS LOCALES
            $tarea->transaccionFTP($ruta.$nombreArchivo, $nombreArchivo,$path.'confirmados/');

            @unlink($ruta.$nombreArchivo);


            //return $response;

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
    
    
    public function registralog($actividad) {
        $conexion = $this->getContainer()->get('Doctrine')->getManager()->getConnection();
        //$ip = $this->container->get('request')->getClientIp();
        $fecha = new \DateTime('now');
        
        $sql=' INSERT INTO log_administrador (tiempo, actividad) VALUES(?,?) ';
        $aux=$conexion->prepare($sql);
        $aux->bindValue(1,$fecha->format('Y-m-d H:i:s'));
        $aux->bindParam(2,$actividad);
        $aux->execute();

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

        $message=new \Swift_Message();
        $message->setSubject('Pedido Convenios modificado por FTP');
        //$message->setFrom($this->getContainer()->getParameter('administrador_email'),$this->getContainer()->getParameter('administrador_nombre'));
        $message->setFrom('sip.convenios@coopidrogas.com.co',$this->getContainer()->getParameter('administrador_nombre'));
        $message->setPriority(1);

        $em= $this->getContainer()->get('Doctrine')->getManager();
        $administradores=$em->getRepository('FTPAdministradorBundle:Administrador')->findBySeguimiento(1);

        $message->setBcc(array('j.restrepo@waplicaciones.co'=>'Julián Restrepo.'));
      
        $arrayAdministradores=array();
        /*foreach ($administradores as $administrador) {
             if (!filter_var($administrador->getEmail(), FILTER_VALIDATE_EMAIL) === false ) 
                $arrayAdministradores[$administrador->getEmail()]=$administrador->getNombre();
        }
        
        $message->setTo($arrayAdministradores);*/
        $message->addTo($this->getContainer()->getParameter('administrador_email'),$this->getContainer()->getParameter('administrador_nombre'));

        if($productos == 0 && $consecutivo == 0 && $proveedorNombre == 0 && $clienteNombre == 0 && $clienteCodigo == 0 && $cupo == 0){

            $mensaje='<h3>Tarea de Convenios ejecutada</h3><p>No se encontraron archivos para procesar.</p>';
            $message->setBody($mensaje,
                'text/html'
            );
        }else{

            $path = $this->getContainer()->get('kernel')->getRootDir().'/../web/';
            //$imageLogo2 = $message->embed(\Swift_Image::fromPath($path.'img/nuevo_logo_copi.png'));    
            set_time_limit(0);
            ini_set('memory_limit', '2048M');
            
            $message->setBody($this->getContainer()->get('templating')->render('FTPAdministradorBundle:Proveedor:emailPedido.html.twig',
                array(
                    'detalle' => $productos,'codPedido' =>$consecutivo,
                    'proveedorNombre'=>$proveedorNombre,'clienteNombre'=>$clienteNombre,'clienteCodigo'=>$clienteCodigo,
                    'cupo'=>$cupo
                )),
                'text/html'
            );
        }

        try{
            $this->getContainer()->get('mailer')->send($message);
            return true;
        }catch(\Exception $e){
            $this->registralog($e->getMessage());
            $data['noEnviados'] = 1;
            return false;
        }
    }
}
?>
