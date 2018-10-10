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

class procesarArchivosConveniosCommand extends ContainerAwareCommand{

    private $message='';

    protected function configure(){
        parent::configure();
        $this->setName('procesarPedidos:convenios')->setDescription('Procesa los archivos de pedidos para convenios');
    }
    protected function execute(InputInterface $input, OutputInterface $output){
        exit('mantenimiento procesarPedidos:convenios');
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

        //SE PONE EN COMENTARIO LA BUSQUEDA DE FACTURAS PARA HACER PRUEBAS
        /****************BUSQUEDA DE FACTURAS REGISTRADAS********************/
        $facturasConfirmadas = array();
        /*$output->writeln("Consultando Facturas registradas ");
        $facturasRegistradas = $emc->createQuery("SELECT ef.numeroFactura, p.id AS proveedor, ef.estadoProcesamiento FROM FTPAdministradorBundle:EstadoFactura ef JOIN FTPAdministradorBundle:Proveedor p WITH ef.proveedor = p.id  ")->getResult();
        foreach($facturasRegistradas as $facturaRegistrada){
            $facturasConfirmadas[$facturaRegistrada['numeroFactura']]['factura'] = $facturaRegistrada['numeroFactura'];
            $facturasConfirmadas[$facturaRegistrada['numeroFactura']]['proveedor'] = $facturaRegistrada['proveedor'];
            $facturasConfirmadas[$facturaRegistrada['numeroFactura']]['estado'] = $facturaRegistrada['estadoProcesamiento'];
        }*/
        /**********************************/



        //Generar pedido.
        if($bandera){
            $bandera=false;

            foreach($proveedores as $proveedor){
                $output->writeln("Generando pedido. proveedor NIT: ".$proveedor['nit']);
                $this->registralog("Generando pedido. proveedor NIT: ".$proveedor['nit']);
                $informe[$proveedor['nit']]=$this->procesarPedidoMultipleAction($proveedor,$proveedor['carpetaConvenios']."/", $output, $arrayProveedoresConvenios,$dataClientes,$facturasConfirmadas); //Recuperar CSV y generar pedido en Convenios.
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
    
    
    public function procesarPedidoMultipleAction($proveedor,$path, $output, $arrayProveedoresFtp, $dataClientes,$facturasConfirmadas) {

        set_time_limit(0);
        ini_set('memory_limit', '2048M');
              
        //conxiones.
        $em=$this->getContainer()->get('doctrine')->getManager();
        $emc=$this->getContainer()->get('doctrine')->getManager('convenios');
        $ems = $this->getContainer()->get('doctrine')->getManager('sipasociados');
        $dir = $this->getContainer()->get('kernel')->getRootDir().'/../web/';
        
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

        $output->writeln("Se incia la tarea de convenios");
        $this->registralog("Se incia la tarea de convenios");

        //servicios
        $tarea=$this->getContainer()->get('utilidadesAdministrador');
        $tarea2=$this->getContainer()->get('generarArchivos');

        $utilidades=$this->getContainer()->get('utilidadesAdministrador');
          
         
        /*$transferencista=$emc->createQueryBuilder()
        ->select('t.id,t.nombres AS transfNombres,p.nombre AS provNombre')
        ->from('FTPAdministradorBundle:Proveedor','p')
        ->join('FTPAdministradorBundle:Transferencista','t','WITH','p.id=t.proveedor')
        ->where('p.codigoCopidrogas=?1')
        ->setParameter(1,$proveedor['nit'])
        ->setMaxResults(1)
        ->getQuery()->getOneOrNullResult();*/

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

        $output->writeln("Se abre la conexion FTP para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
        
        //SE PONE EN COMENTARIO LA CONEXION FTP PARA HACER PRUEBAS
        $conexionFTP=@ftp_connect($this->getContainer()->getParameter('host_ftp'),$this->getContainer()->getParameter('port'));
        @ftp_login($conexionFTP,$this->getContainer()->getParameter('user_ftp'),$this->getContainer()->getParameter('pass_ftp'));
        
        //Validamos si el directorio existe

        if($path == "/"){
            $output->writeln("4.2.2 El proveedor [".$proveedor['proveedor']."] no tiene carpeta asignada para procesar archivos.");
            $this->registralog("4.2.2 El proveedor [".$proveedor['proveedor']."] no tiene carpeta asignada para procesar archivos.");
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
                        
                        $this->registralog("4.2.1 Se crea la carpeta procesados para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
                        
                    }else{
                        @ftp_chdir($conexionFTP,'../');
                    }

                    if(!@ftp_chdir($conexionFTP,'inventario')){
                        @ftp_mkdir($conexionFTP,'inventario');
                        
                        $this->registralog("4.2.1 Se crea la carpeta inventario para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
                    }else{
                        @ftp_chdir($conexionFTP,'../');   
                    }

                    if(!@ftp_chdir($conexionFTP,'pendienteActualizar')){
                        @ftp_mkdir($conexionFTP,'pendienteActualizar');
                        
                        $this->registralog("4.2.1 Se crea la carpeta pendienteActualizar para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
                    }else{
                        @ftp_chdir($conexionFTP,'../');   
                    }

                    if(!@ftp_chdir($conexionFTP,'confirmados')){
                        @ftp_mkdir($conexionFTP,'confirmados');
                        
                        $this->registralog("4.2.1 Se crea la carpeta confirmados para el proveedor ".$proveedor['proveedor']." [".$proveedor['nit']."]");
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
 
                    $output->writeln("Se buscan archivos a procesar ");
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
                           
                            //if ($i == 1) {//pruebas

                            
                                $output->writeln("Se inicia el procesamiento del archivo ".$archivo);
                                $this->registralog("Se inicia el procesamiento del archivo ".$archivo." tarea Convenios");

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
                                                        //$datos[1] = trim((string)$datos[1]);
                                                        $datos[1] = trim(str_pad((string)$datos[1], 9, "0", STR_PAD_LEFT));
                                                        $datos[2] = trim($datos[2]);
                                                        $datos[3] = trim($datos[3]);
                                                        $datos[4] = trim($datos[4]);
                                                        $datos[5] = trim($datos[5]);
                                                        $datos[6] = (isset($datos[6]))?trim($datos[6]):'';

                                                        $transferencista['id'] = $datosTransferencista[trim($datos[5])];
                                                   



                                                            if( ($datos[0]!='') && ( is_numeric($datos[2]) && ( strlen($datos[2])<=3 && $datos[0]>=1 ))){//validar cÃ³digo de la DroguerÃ­a y cantidad solicitada.



                                                                if($datos[2]>=1){
                                                                    //si no tiene consecutivo es producto para pedido nuevo
                                                                    $drogueriasArchivo[$datos[0]][$datos[3]] = array('cantidad' => $datos[2], 'producto' => $datos[3], 'codigo' => $datos[1]);
                                                                    if(isset($datos[6]) && $datos[6]!=''){
                                                                        $drogueriasRemision[$datos[0]] = $datos[6];
                                                                    }
                                                                }



                                                               
                                                            }else{
                                                                $output->writeln("Buscando cliente: ".$datos[0]);
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

                                                $output->writeln("Buscando cliente: ".$datos[0]);
                                                $cliente=$ems->createQueryBuilder('c')->select('c.id,c.cupoAsociado,c.tipoCliente,c.codigo,c.centro,c.ciudad,c.depto,c.drogueria,c.email')
                                                            ->from('FTPAdministradorBundle:Cliente','c')
                                                            ->where('c.codigo=?1')
                                                            ->setMaxResults(1)
                                                            ->setParameter(1,$codigo)
                                                            ->getQuery()->getOneOrNullResult();

                                                if($cliente){
                                                    $output->writeln("Cliente encontrado : ".$datos[0]);
                                                   
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

                                                        $output->writeln("Buscando en el inventario: ".$codProducto);

                                                        /*$inventario = $emc->createQueryBuilder('ip')->select('ip')
                                                            ->from('FTPAdministradorBundle:InventarioProducto','ip')
                                                            ->where('ip.codigoBarras=?1')
                                                            ->setMaxResults(1)
                                                            ->setParameter(1,$codProducto)
                                                            ->setMaxResults(1)
                                                            ->getQuery()->getResult();*/

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

                                                            $this->registralog("se inserta el producto ".$producto['producto']." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."] archivo[".$archivo."]");                                                      
                                                            $output->writeln("se inserta el producto ".$producto['producto']." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."]archivo[".$archivo."]");
                                                            
                                                            $pedidoDescripcion=new PedidoDescripcion();

                                                            $pedidoDescripcion = $this->crearPedidosDescripcion($pedidoDescripcion, $inventario[0], $cliente['id'], $producto['cantidad'],$emc,$codigo ,$transferencista['id']);
                                                            $emc->persist($pedidoDescripcion);
                                                            $emc->flush();

                                                            $contadorProductos++;
                                                            $output->writeln("Insertado: ".$contadorProductos);

                                                            /*if($pedidoDescripcion->getId()){
                                                                $productosIngresados[$codigo][ $inventario[0]->getCodigoBarras() ]=$inventario[0]->getCodigoBarras();
                                                                $informe[$cantArchivos][$datos[0]]['insertados'][$datos[3]]=$datos[3];
                                                            }else{
                                                                $informe[$cantArchivos][$datos[0]]['noIsertados'][]=$datos[3];
                                                            }  */ 

                                                        }else{
                                                            $output->writeln(" NO se inserta el producto ".$codProducto." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."] ");
                                                            $this->registralog("Producto NO encontrado ".$codProducto." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."]");

                                                            $logRespuesta[]= "Producto NO encontrado ".$codProducto." para el pedido de la drogueria ".$codigo." cantidad[".$producto['cantidad']."]";
                                                        }

                                                    }
                                                }else{
                                                     $output->writeln("Cliente no encontrado : ".$datos[0]); 
                                                     $logRespuesta[]= "Cliente no encontrado : ".$datos[0];
                                                }

                                            }//fin foreac
                                           // dump($clientesConsultados);
                                           // dump($drogueriasArchivo);

                                            //se crea el pedido.
                                            foreach($drogueriasArchivo as $codigoDrogueria => $value){

                                                $totalPedido=$utilidades->totalPedido($codigoDrogueria,0,0,$emc,$proveedor['nit']);
                                                $cupo = $utilidades->retornoEstadoCupo($codigoDrogueria);

                                                if ($cupo['estadoCupo'] == 'Activo' || $cupo['estadoCupo'] == 'Cupo Disponible') {

                                                    if ($totalPedido['total'] <= $cupo['cupoActual']) {

                                                        $minimoPedido = $emc->getRepository('FTPAdministradorBundle:VariablesSistema')->find(1);
                                                        if($minimoPedido){$minimo=$minimoPedido->getMinimoPedido();}else{$minimo=0;}
                                                        //$minimo=0;

                                                        if($totalPedido['total'] >= $minimo && $totalPedido['total'] > 0 ){
                                                            
                                                            $output->writeln("se procede a la confirmacion del pedido de la drogueria ".$codigoDrogueria);
                                                            $this->registralog("se procede a la confirmacion del pedido de la drogueria ".$codigoDrogueria);

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

                                                            $output->writeln( "Pedido: " .$pedido->getConsecutivo(). " NumProductos:" . $contador['cantidadUni'] . " Drogueria:" . $codigoDrogueria);

                                                            $pedidosTotales++;

                                                            $informe[$cantArchivos][$codigoDrogueria]['drogueria']=$codigoDrogueria;
                                                            $informe[$cantArchivos][$codigoDrogueria]['cantUnit']=$contador['cantidadUni'];
                                                            $informe[$cantArchivos][$codigoDrogueria]['canTotal']=$contador['cantidad'];
                                                            $informe[$cantArchivos][$codigoDrogueria]['totalPedido']=$totalPedido['total'];
                                                            $informe[$cantArchivos][$codigoDrogueria]['pedido']=$pedido->getConsecutivo();
                                                            
                                                            $output->writeln("pedido generado con el consecutivo ".$pedido->getConsecutivo()." para la drogueria ".$codigoDrogueria);
                                                            
                                                            
                                                            $this->registralog('4.2.7 Se genera el pedido con el consecutivo ['.$pedido->getConsecutivo().']. para la drogueria '.$codigoDrogueria.' con el proveedor '.$proveedor['proveedor']);
                                                            
                                                            if($pedido->getId()){
                                                             

                                                                $output->writeln("se actualiza el detalle del pedido ".$pedido->getConsecutivo()." para la drogueria ".$codigoDrogueria);

                                                                $emc->createQueryBuilder()->update('FTPAdministradorBundle:PedidoDescripcion','pd')
                                                                ->set('pd.productoEstado','?1')
                                                                ->set('pd.pedido','?2')
                                                                ->where('pd.productoEstado=10  AND pd.drogueriaId=?3 AND pd.transferencista=?4')
                                                                ->setParameter(1,1)
                                                                ->setParameter(2,$pedido->getId())
                                                                ->setParameter(3,$codigoDrogueria)
                                                                ->setParameter(4,$transferencista['id'])
                                                                ->getQuery()->execute();

                                                                //DESABILITADO DEL LOG DE CREACION DE ARCHIVO RESULTANTE
                                                                //$tarea->registralogProveedor($informe[$cantArchivos]['archivo']['name'],count($informe[$cantArchivos][$codigoDrogueria]),$codigoDrogueria,1,$informe[$cantArchivos][$codigoDrogueria]['pedido'],$totalPedido['total'] ,$informe[$cantArchivos][$codigoDrogueria]['canTotal'],$proveedor['id']); 

                                                                if( isset( $clientesConsultados[$codigoDrogueria]['cupoAsociado'], $clientesConsultados[$codigoDrogueria]['drogueria'] ) ){

                                                                    /*if($this->enviarCorreo($productosRegistrados, $pedido->getConsecutivo(), $pedido->getProveedor()->getNombre(), $clientesConsultados[$codigoDrogueria]['drogueria'], $codigoDrogueria, $clientesConsultados[$codigoDrogueria]['cupoAsociado'])){
                                                                        $output->writeln('Se envio correo para el pedido : '.$pedido->getConsecutivo());
                                                                        $this->registralog('Se envio correo para el pedido : '.$pedido->getConsecutivo());

                                                                    }else{
                                                                        $output->writeln('Se creo el pedido : '.$pedido->getConsecutivo().' Pero no fue posible enviar el correo de confirmacion. Revise la configuracion del correo.');
                                                                        $this->registralog('Se creo el pedido : '.$pedido->getConsecutivo().' Pero no fue posible enviar el correo de confirmacion. Revise la configuracion del correo.');
                                                                    }*/
                                                                }else{
                                                                    $output->writeln('Se creo el pedido : '.$pedido->getConsecutivo().' Pero no fue posible enviar el correo de confirmacion. Info del Asociado incompleta.');
                                                                    $this->registralog('Se creo el pedido : '.$pedido->getConsecutivo().' Pero no fue posible enviar el correo de confirmacion. Info del Asociado incompleta.');

                                                                }

                                                            }else{
                                                                $informe[$cantArchivos][$codigoDrogueria]=$this->descartarDetallePedido($codigoDrogueria,$transferencista,$emc,$informe[$cantArchivos][$drogueria['codigo']]);
                                                                $output->writeln("se descarta el  pedido ".$pedidosTotales." para la drogueria ".$codigoDrogueria);

                                                                $this->registralog("se descarta el  pedido ".$pedidosTotales." para la drogueria ".$codigoDrogueria);
                                                            }

                                                        }else{

                                                            $output->writeln("no cumple el  pedido  ".$pedidosTotales." para la drogueria ".$codigoDrogueria);

                                                            // si el total del pedido no alcanza el mínimo permitido los items son descargados
                                                            //para la iteración en curso.
                                                            if (isset($informe[$cantArchivos][$codigoDrogueria])) {  

                                                                $output->writeln("se elimina el pedido de la drotgueria ".$codigoDrogueria." por no cumplir con el minimo ");
                                                            
                                                                $this->registralog('4.2.6 Error. Se elimina la informacion del pedido de la drogueria '.$codigoDrogueria.' por no cumplir con el valor minimo del pedido.');
                                                                
                                                                $informe[$cantArchivos][$codigoDrogueria]=$this->descartarDetallePedido($codigoDrogueria,$transferencista['id'],$emc,$informe[$cantArchivos][$codigoDrogueria]);
                                                                $arrayEstado[$drogueria['codigo']]['noAceptado']= 1;
                                                                $informe[$cantArchivos][$drogueria['codigo']]['totalPedido']=$totalPedido['total'];

                                                            }

                                                        }

                                                    } else {

                                                        $remision = (isset($drogueriasRemision[$codigoDrogueria]))?' remision: '.$drogueriasRemision[$codigoDrogueria]:'';
                                                        //$log = ' No se cargo el pedido para la drogueria :'.$codigoDrogueria.' por cupo aprobado. Total del pedido: '.number_format($totalPedido['total'],0,'','.').' '.$remision.' cupo actual:  '.number_format($cupo['cupoActual'], 0, '', '.');
                                                        $log = 'El pedido no puede ser cargado, el asociado ['.$codigoDrogueria.'] debe comunicarse con Coopidrogas ';
                                                        $productos =  $drogueriasArchivo[$codigoDrogueria];
                                                        $this->eliminarProductos($codigoDrogueria, $productos);
                                                        $this->registralog($log);
                                                        $logRespuesta[]= $log;
                                                         

                                                    }//end if cupo insuficiente

                                                } else {

                                                    $remision = (isset($drogueriasRemision[$codigoDrogueria]))?'remision: '.$drogueriasRemision[$codigoDrogueria]:'';
                                                    //$log = 'No se ha procesado la drogueria :'.$codigoDrogueria.' por estado de cupo: '.$cupo['estadoCupo'].' '.$remision;
                                                    $log = 'El pedido no puede ser cargado, el asociado ['.$codigoDrogueria.'] debe comunicarse con Coopidrogas ';
                                                    $this->registralog($log);
                        
                                                    $logRespuesta[]= $log;

                                                }//end if no hay cupo activo

                                            }//fin foreach

                                            //SE ACTUALIZA LA CANTIDAD FINAL DEL PEDIDO
                                                            

                                           // exit("fin");


                                            unset($pedidoNocreado);
                                            $informe[$cantArchivos]['pedidosTotales']=$pedidosTotales;

                                            //Registrar información de último pedido cargado, cantidad y última transacción con Convenios.
                                            $this->updateProveedor($proveedor['nit'],$pedidosTotales, 1);

                                            //Registrar actividad en logsAdministraodor
                                            $this->registralog('4.2.8 Cargar '.$pedidosTotales.' pedido(s) para el proveedor '.$proveedor['proveedor'].' ['.$proveedor['nit'].'] ');
                                            
                                            //SE COMENTAREA LA CREACION DEL ARCHIVO DE LOG MIENTRAS PRUEBAS
                                            $txt[$cantArchivos]=$tarea2->generarLog($informe[$cantArchivos],$path, $this->getContainer()->get('kernel')->getRootDir().'/../web/', $logRespuesta, $archivo);
                                            $cantArchivos++;

                                        }else{

                                            $informe['error'] = 'Estructura del archivo incorrecta. Archivo['.$archivo.'] vacio.';

                                            $logRespuesta[]= "Estructura del archivo incorrecta. Archivo [".$archivo."] vacio";

                                            $txt[$cantArchivos]=$tarea2->generarLog($informe,$path, $this->getContainer()->get('kernel')->getRootDir().'/../web/', $logRespuesta, $archivo);
                                            $cantArchivos++;
                                        }


                                        

                                    }else{
                                        $informe['error'] = 'Por favor verifique la estructura del archivo['.$archivo.'].No fue posible iniciar la operación.';

                                        $logRespuesta[]= "Estructura del archivo incorrecta.";

                                        $txt[$cantArchivos]=$tarea2->generarLog($informe,$path, $this->getContainer()->get('kernel')->getRootDir().'/../web/', $logRespuesta, $archivo);
                                        $cantArchivos++;
                                    }

                                    fclose($fp);
                                    
                                }else{
                                    $informe[$cantArchivos]['error'] = 'Error. Verifique la ruta y el nombre del archivo['.$archivo.'].';
                                    $this->registralog('4.2.3 Error. Verifique la ruta y el nombre del archivo['.$archivo.'].');
                                    $output->writeln('Error. Verifique la ruta y el nombre del archivo['.$archivo.'].');
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


                        /*******INICIA EL PROCESO DE CONFIRMACION DE FACTURAS*********/
                        

                        /**************************************************/


                        /*************************CREACION DEL ARCHIVO DE PEDIDOS CONFIRMADOS****************************/

                        

                        
                        /***************************************************************************************************/
                    }else{

                        //$this->enviarCorreo(0, 0, 0, 0, 0, 0 );
                        $sinArchivos[]='El proveedor  '.$proveedor['proveedor'].' .No tiene archivos para procesar a Convenios. ';
                        $this->registralog("4.2.2 El proveedor [".$proveedor['proveedor']."] no tiene archivos para procesar a Convenios.");
                    }
                      
                }
                
                // se actualizan pedidos pendientes y se crean archivos para actualizar 
                //$this->conveniosPedidosPendientesActualizar($pedidosGenerados, $path, $arrayProveedoresFtp[$proveedor['nit']],$output, $dataClientes);
            }
        } 
        //SE COMENTAREA EL CIERRE DE LA CONEXION FTP    
        //ftp_close($conexionFTP);
        $em->getConnection()->close();
        $emc->getConnection()->close();
        $ems->getConnection()->close();

        return array('informe' =>$informe ,'log'=>$txt,'sinArchivos'=>$sinArchivos);
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
                $informe['noIsertados'][]='El total del pedido no alcanzaba el mínimo necesario. '.$v['pdtoCodigoBarras'];
                
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

    public function crearPedidosDescripcion($pedidoDescripcion, $inventario, $idDrogueria, $cantidad,$emc,$codigoAsociado,$idTransferencista) {

        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $pedidoDescripcion->setPdtoId($inventario->getId());// id del producto.
        $pedidoDescripcion->setDrogueriaId($codigoAsociado);
        $pedidoDescripcion->setTransferencista($emc->getReference('FTPAdministradorBundle:Transferencista',$idTransferencista));
        $pedidoDescripcion->setCantidadPedida($cantidad);
        $pedidoDescripcion->setProductoCodigo($inventario->getCodigo());
        $pedidoDescripcion->setProductoCodigoBarras($inventario->getCodigoBarras());
        $pedidoDescripcion->setProductoDescripcion($inventario->getDescripcion());
        $pedidoDescripcion->setProductoPresentacion($inventario->getPresentacion());
        $pedidoDescripcion->setProductoPrecio((string)$inventario->getPrecio());
        $pedidoDescripcion->setProductoMarcado(0);
        $pedidoDescripcion->setProductoReal((string)$inventario->getPrecio());
        $pedidoDescripcion->setProductoIva($inventario->getIva());
        $pedidoDescripcion->setLinea($emc->getReference('FTPAdministradorBundle:Linea',$inventario->getLinea()->getId()));
        $pedidoDescripcion->setProductoDescuento($inventario->getDescuento());
        $pedidoDescripcion->setProductoFecha(new \DateTime());
        $pedidoDescripcion->setProductoEstado(10);
        $pedidoDescripcion->setCantidadFinal($cantidad);
        $pedidoDescripcion->setProductoFoto($inventario->getFoto());

        return $pedidoDescripcion;
    }
    
    
    public function updateProveedor($nitProveedor,$pedidosTotales,$tipo){

        $em=$this->getContainer()->get('doctrine')->getManager();
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
    
    
    public function conveniosPedidosPendientesActualizar($idsPedidos, $path, $idProveedorConvenios, $output, $dataClientes){
        
        
        $directorio = false;
        $arrayArchivosAProcesar = array();
        $dir = $this->getContainer()->get('kernel')->getRootDir().'/../web/';
        $ems = $this->getContainer()->get('doctrine')->getManager('sipasociados');
        
        
        //SE DEJA COMENTARIO MIENTRAS PRUEBAS
        $conexionFTP=@ftp_connect($this->getContainer()->getParameter('host_ftp'),$this->getContainer()->getParameter('port'));
        @ftp_login($conexionFTP,$this->getContainer()->getParameter('user_ftp'),$this->getContainer()->getParameter('pass_ftp'));
        
        $output->writeln("Se abre conexion ftp");

        
        if(@ftp_chdir($conexionFTP,$path.'pendienteActualizar/') == false){
            
            
            $raiz=explode('/',$path);
            
            if(@ftp_chdir($conexionFTP,$raiz[0]) == false ){
                
                $directorio=false;
            }else{
                
                if(@ftp_chdir($conexionFTP,$raiz[1]) == false){
                    
                    $directorio=false;
                    
                }else{
                    if(@ftp_chdir($conexionFTP,'pendienteActualizar/') == false){
                        $directorio=false;
                    }else{
                        $directorio=true;
                    }
                }
            }
            
        }else{
            $directorio=true;
        }
        
        
        //SE DEJA LA BANDERA EN TRUE PARA PRUEBAS
        //$directorio = true;
        if($directorio){

            // creacion de archivo de pedidos por actualizar con los ids de los pedidos que llegan
            $this->archivoPedidosPendientes($idProveedorConvenios, $idsPedidos, $path, $dataClientes);
                
        }
        
        //ftp_close($conexionFTP);
    }
    
    public function actualizarPedidosConvenios($archivoLocal, $idProveedorConvenios, $output){
        
        $emc = $this->getContainer()->get('doctrine')->getManager('convenios');
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
                        $output->writeln("dato 0".$data[0]."dato 0");
                        if($aux>=1 && trim($data[0]) != ""){
                           if((isset($data[8])) && ($data[8]!='')){//funcion para validar datos.
                                    
                                    
                                    $pedido = $emc->getRepository('FTPAdministradorBundle:Pedido')->findOneByConsecutivo($data[8]);
                                    $output->writeln("Se busca el pedido ".$data[8]);
                                    if($pedido){
                                        if($pedido->getPedidoProcesado() >= 1){

                                        }else{
                                            if(($pedido->getFechaEnviadoProveedor() != $data[14]||
                                            $pedido->getFechaRecibidoCopidrogas() != $data[17] ||
                                            $pedido->getFechaEnviadoAsociado() != $data[19] ) &&
                                            $data[15] != ""){
                                                
                                                $output->writeln("Se procesa el pedido ".$data[8]." con factura ".$data[15]);

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
                                                $output->writeln("Se actualiza el pedido ".$data[8]);
                                                
                                                $this->registralog("4.3.1 El pedido [".$data[8]."] ha sido actualizado");

                                                //$actualizado.='<br>Pedidocon consecutivo = <b>'.$data[8].'</b> actualizado <br>';

                                            }else{
                                                //$noActualizado.='<br>El pedido con consecutivo de barras = <b>'.$data[0].'</b> ya estaba actualizado.<br />';
                                                //$actualizado.='<br>El pedido con consecutivo de barras = <b>'.$data[8].'</b> ya estaba actualizado.<br />';
                                                $output->writeln("El pedido ".$data[8]." no se puede actualizar debido a la falta de informacion");
                                                
                                                $this->registralog("4.3 El pedido [".$data[8]."] no se puede actualizar debido a la falta de informacion");
                                            }
                                        }

                                    }else{
                                        //$excepcion.='<br>El pedido con consecutivo =  <b>'.$data[8].'</b> no existe.';
                                        $output->writeln("No se encontro el pedido ".$data[8]);
                                        $this->registralog('4.3 No se encuentra el pedido con consecutivo ['.$data[8].'].');
                                    }

                                //}
                           }else{
                               $this->registralog('4.3 No se encuentra el pedido con consecutivo ['.$data[8].'].');
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
                        $output->writeln("Se registra la factura ".$k);
                    }
         
            }
        }
        
        return $factura;
    }
    
    
    public function archivoPedidosConfirmados($numerosFactura, $idProveedorConvenios, $path, $dataClientes, $output){
        
            set_time_limit(0);
            ini_set('memory_limit', '1024M');

            $ruta=$this->getContainer()->get('kernel')->getRootDir().'/../web/';
            
            $tarea=$this->getContainer()->get('generarArchivos');

            
            $em = $this->getContainer()->get('doctrine')->getManager('convenios');


            if(count($numerosFactura) > 0){
                
                $output->writeln("cantidad de facturas para los pedidos en el archivo = ".count($numerosFactura));
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
            
            $tarea->transaccionFTP($ruta.$nombreArchivo, $nombreArchivo,$path.'confirmados/');

            //@unlink($ruta.$nombreArchivo);


            //return $response;

    }
    
    public function archivoPedidosPendientes($idProveedorConvenios, $idsPedidos, $path, $dataClientes){
        
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $ruta=$this->getContainer()->get('kernel')->getRootDir().'/../web/';

        $tarea=$this->getContainer()->get('generarArchivos');

        if(count($idsPedidos) > 0){
            
            $em = $this->getContainer()->get('doctrine')->getManager('convenios');
        
            $pedidos = $em->createQuery("SELECT p.consecutivo,pd.productoCodigo,pd.productoDescripcion,pd.cantidadFinal,pd.productoPrecio, pd.bonificacion, p.numeroCajas, p.remision, p.numeroFactura, p.codigoDrogueria FROM FTPAdministradorBundle:Pedido p
                                            LEFT JOIN p.transferencista t
                                            LEFT JOIN p.proveedor pro
                                            LEFT JOIN FTPAdministradorBundle:PedidoDescripcion pd WITH p.id = pd.pedido 
                                            WHERE pro.id =:idProveedor AND p.id IN ('".implode("','",$idsPedidos)."') ")
                    ->setParameter('idProveedor', $idProveedorConvenios)->getResult();

            $estado=array(0=>'Confirmado',1=>'Enviado Proveedor',2=>'Recibido Copidrogas',3=>'Enviado Asociado',99=>'Eliminado');


             if($idProveedorConvenios != null){

                 $template= $this->getContainer()->get('templating')->render('FTPAdministradorBundle:Proveedor:pedidosPendientesCsv.html.twig',array('pedidos'=>$pedidos,'estado'=>$estado,'cliente'=>$dataClientes));

                 $nombreArchivo="pedidos_pendientes_actualizacion_".date('Y-m-d_H_m_s').".csv";
                 $archivo = fopen($ruta.$nombreArchivo,'w');

                fwrite ($archivo,$template);
                fclose ($archivo);

                $tarea->transaccionFTP($ruta.$nombreArchivo, $nombreArchivo,$path.'pendienteActualizar/');

                @unlink($ruta.$nombreArchivo);
                
                $this->registralog("4.3.2 Se genera el archivo [".$nombreArchivo."] con la informacion de los pedidos pendientes por actualizar");

            }
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
        $message->setSubject('Pedido cargado por FTP a Convenios ');
        //$message->setFrom($this->getContainer()->getParameter('administrador_email'),$this->getContainer()->getParameter('administrador_nombre'));
        $message->setFrom('sip.convenios@coopidrogas.com.co',$this->getContainer()->getParameter('administrador_nombre'));
        $message->setPriority(1);

        $em= $this->getContainer()->get('Doctrine')->getManager();
        $administradores=$em->getRepository('FTPAdministradorBundle:Administrador')->findBySeguimiento(1);

        $message->setBcc(array('j.casas@waplicaciones.co'=>'Julián Casas.'));
      
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

    private function eliminarProductos ($codigoDrogueria, $productos) {

        $emc = $this->getContainer()->get('doctrine')->getManager('convenios');
        $conexion = $emc->getConnection();
        $sqlDelete = '';

        foreach ($productos as $registro) {
            
            $sqlDelete .= 'delete from pedido_descripcion where producto__estado=10 and drogueria_id='.$codigoDrogueria
                       .' and producto_codigo='.$registro['codigo'].' and producto_codigo_barras='.$registro['producto'].' and pedido_id is null ;';

        }//end foreach

        if ($sqlDelete != '')
            $conexion->query($sqlDelete);

        $conexion->close();

    }//end function


}//end class
?>
