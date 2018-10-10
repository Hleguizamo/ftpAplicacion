<?php
namespace FTP\AdministradorBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use FTP\AdministradorBundle\Entity\EstadoFactura;

class actualizarProcesarPedidosCommand extends ContainerAwareCommand{
    protected function configure(){
        parent::configure();
        $this->setName('actualizar:pedidos')->setDescription('Actualiza los pedidos pendientes del proveedor y procesa las facturas del proveedor');
    }
    protected function execute(InputInterface $input, OutputInterface $output){
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        $emc = $this->getContainer()->get('doctrine')->getManager('convenios');
        
        
        $proveeresFtp = $em->getRepository('FTPAdministradorBundle:Proveedores')->findAll();
        
        foreach($proveeresFtp as $proveedor){
            
            $proveedorConvenios = $emc->getRepository('FTPAdministradorBundle:Proveedor')->findOneBy(array('codigoCopidrogas' => $proveedor->getNitProveedor()  ));
            
            if($proveedorConvenios){
                $output->writeln("Se valida si el proveedor ".$proveedorConvenios->getNombre()." tiene pedidos pendienes por actualizar");
                $this->conveniosPedidosPendientesActualizar($proveedor->getCarpetaConvenios(), $proveedorConvenios->getId(), $output);
            }
            
            
        }

        
        $output->writeln("Fin de la tarea.");
    }
    
    
    public function conveniosPedidosPendientesActualizar($path, $idProveedorConvenios, $output){
        
        
        $directorio = false;
        $arrayArchivosAProcesar = array();
        $dir = $this->getContainer()->get('kernel')->getRootDir().'/../web/';
        $ems = $this->getContainer()->get('doctrine')->getManager('sipasociados');
        
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
        
        
        
        if($directorio){
            
            $documentos = ftp_nlist($conexionFTP, ".");
            
            $output->writeln("Se validan los archivos pendientes");

            foreach ($documentos as $file) {
                $output->writeln("Se valida el archivo ".$file);
                if(substr($file, -3)=='csv'||substr($file, -3)=='CSV' ){
                    $arrayArchivosAProcesar[]=$file;
                }
            }
            unset($documentos);
            
            if($arrayArchivosAProcesar){
                
                foreach($arrayArchivosAProcesar as $archivo){
                    
                    ftp_get($conexionFTP,$dir.$archivo,$archivo, FTP_BINARY);
                    
                    $output->writeln("Se intena actualizar el archivo ".$archivo);
                    //respuesta de actualizacion de pedido con los archivos en la carpeta
                    $respuestaActualizacion = $this->actualizarPedidosConvenios($dir.$archivo, $idProveedorConvenios, $output);
                    
                    if(count($respuestaActualizacion) > 0){
                        @unlink($dir.$archivo);
                        
                        //echo $conexionFTP;
                        if(ftp_delete($conexionFTP, $archivo)){
                            
                            // creacion plantilla XLS
                            $this->archivoPedidosConfirmados($respuestaActualizacion, $idProveedorConvenios, $path, $dataClientes, $output);
                        }
                        //$this->archivoPedidosConfirmados($respuestaActualizacion, $idProveedorConvenios, $path, $dataClientes,$output);

                    }
 
                }
            }
            
            // creacion de archivo de pedidos por actualizar con los ids de los pedidos que llegan
            //$this->archivoPedidosPendientes($idProveedorConvenios, $idsPedidos, $path, $dataClientes);
                
        }
        
        ftp_close($conexionFTP);
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
                                                
                                                $output->writeln("Se procesa el pedido ".$data[8]);

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

        $ruta=$this->getContainer()->get('kernel')->getRootDir().'/../web/';

        $tarea=$this->getContainer()->get('generarArchivos');


        $em = $this->getContainer()->get('doctrine')->getManager('convenios');
        
        $pedidos = $em->createQuery("SELECT p FROM FTPAdministradorBundle:Pedido p
                                        LEFT JOIN p.transferencista t
                                        LEFT JOIN p.proveedor pro
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
}
?>