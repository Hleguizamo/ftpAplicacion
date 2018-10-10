<?php

namespace FTP\AdministradorBundle\Services;
use FTP\AdministradorBundle\Entity\sipproveedores\Cliente;
use FTP\AdministradorBundle\Entity\sipproveedores\Inventario;
use FTP\AdministradorBundle\Entity\sipproveedores\Bonificacion;
use FTP\AdministradorBundle\Entity\sipproveedores\InventarioKits;
use FTP\AdministradorBundle\Entity\sipproveedores\DetalleKits;

class GenerarArchivos{

     /**
     * Contenedor de servicios
     */
    private $container;

    /**
     * Costructor de la clase
     */
    public function __construct($container) {
        $this->container = $container;
    }

    /**
    * Acción que genera el archivo plano con información de los asociados.
    * @param $codigoProveedor
    * @return array con el estado de la transacción y el nombre del archivo.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    */
    public function asociados($path,$codigoProveedor,$dir){
        try{

            //Recuperar todo el inventario disponible asociado al código del proveedor. 
            $emsp=$this->container->get('Doctrine')->getManager('sipproveedores');
            $clientes=$emsp->createQueryBuilder()
            ->select('c.codigo,c.drogueria,c.ciudad,c.depto,c.direcion,c.telefono,c.asociado,c.centro,c.ruta,c.bloqueoD')
            ->from('SipproveedoresEntity:Cliente','c')
            ->orderBy('c.codigo', 'ASC')
            ->getQuery()->getResult();

            //generar archivo txt

            $archivo ='Asociados_'.$codigoProveedor.'.txt';
            $local=$dir.$archivo;
            if (file_exists($local)){
                unlink($local);//eliminar el archivo
            }

            $fileHandle = @fopen($local, 'w');
            $texto='';
            
            if ($clientes) {
                foreach ($clientes as $k => $v) {
                    $texto.= $v['codigo'] . ';' . $v['drogueria'] .';'.$v['ciudad'].';'.$v['depto'] .';'.$v['direcion'].';'.$v['telefono'].';'.
                    $v['asociado'].';'.$v['centro'].';'.$v['ruta'].';'.$v['bloqueoD'].';'.$this->bloqueo((string)$v['bloqueoD']) . "\n\r";
                }
                fwrite($fileHandle, $texto);
            }
            fclose($fileHandle);
            unset($clientes,$texto);
            //Cargar archivo por FTP.
            $this->transaccionFTP($local, $archivo,$path);
         }catch(Exception $e){
            return 'Error al generar el archivo : Asociados_'.$codigoProveedor.'.txt';
         }   
         
          return 'Archivo generado :  Asociados_'.$codigoProveedor.'.txt';
        
    }

    /**
        * Acción que genera el archivo plano con información del inventario para el proveedor seleccionado.
        * @param $codigoProveedor
        * @return array con el estado de la transacción y el nombre del archivo.
        * @author Julián casas <j.casas@waplicaciones.com.co>
        * @since 2.6
    */
    public function inventario($path,$codigoProveedor,$dir){

        $emsp=$this->container->get('Doctrine')->getManager('sipproveedores');

        //Recuperar todo el inventario disponible asociado al código del proveedor.
        $inventario=$emsp->createQueryBuilder()
        ->select('i.centro,i.clasificacion,i.material,i.denominacion,i.lote,i.empaque,i.cantidad,i.precioReal,'.
            ' i.precioCorriente,i.impuesto,i.precioMarcado,i.bonificacion,i.codigoBarras,i.division')
        ->from('SipproveedoresEntity:Inventario','i')
        ->where('i.proveedorId=?1')
        ->orderBy('i.centro', 'ASC')
        ->setParameter(1,$codigoProveedor)
        ->getQuery()
        ->getResult();
        
        $texto='';

        try{
            //generar archivo txt
            $archivo ='InvTrans'.$codigoProveedor.'.txt';
            $local=$dir.$archivo;
            if (file_exists($local)){
                unlink($local);//eliminar el archivo
            }

            $fileHandle = @fopen($local, 'w');
            
            
            if ($inventario) {
                foreach ($inventario as $k => $v) {
                    $texto.= $v['centro'].';'.$v['clasificacion'].';'.$v['division'].';'.$v['material'].';'.$v['denominacion'].';'.$v['lote'].';'.$v['empaque'].';'.$v['cantidad'].';'.
                    $v['precioReal'].';'.$v['precioCorriente'].';'.$v['impuesto'].';'.$v['precioMarcado'].';'.$v['bonificacion'].';'.$v['codigoBarras'].';'."\n\r";
                    
                }
                fwrite($fileHandle, $texto);
            }
            fclose($fileHandle);
            //Cargar archivo por FTP.
            $this->transaccionFTP($local, $archivo,$path);
        }catch(Exception $e){
            return ' Error al generar el inventario : InvTrans'.$codigoProveedor.'.txt';
        }   
        unset($inventario, $texto);
        return ' Archivo generado : InvTrans'.$codigoProveedor.'.txt';
        
    }

    /**
    * Acción que genera el archivo plano con información de bonificaciones para el proveedor seleccionado.
    * @param $codigoProveedor
    * @return array con el estado de la transacción y el nombre del archivo.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    */
    public function bonificaciones($path,$codigoProveedor,$dir){

        //Recuperar las bonificaciones disponibles asociadas al código del proveedor.
        $emsp=$this->container->get('Doctrine')->getManager('sipproveedores');

        $inventario=$emsp->createQueryBuilder()
        ->select('b.centro,b.codigo,b.definicion,b.uniSol,b.cantidad1,b.obsequio,b.cantidad2,b.precio')
        ->from('SipproveedoresEntity:Bonificacion','b')
        ->where('b.codCopiProveedor=?1')
        ->setParameter(1,$codigoProveedor)
        ->orderBy('b.centro','ASC')
        ->getQuery()
        ->getResult();
        $texto='';

        try{
            //generar archivo txt
            $archivo ='InvBonificacion'.$codigoProveedor.'.txt';
            $local=$dir.$archivo;
            if (file_exists($local)){
                unlink($local);//eliminar el archivo
            }

            $fileHandle = fopen($local, 'w');
                        
            if($inventario) {
                foreach ($inventario as $k => $v) {
                    $texto.= $v['centro'].';'.$v['codigo'].';'.$v['definicion'].';'.$v['uniSol'].';'.$v['cantidad1'].';'.$v['obsequio'].';'.$v['cantidad2'].';'.
                    $v['precio'].';'."\n\r";
                    
                }
                fwrite($fileHandle, $texto);
            }
            fclose($fileHandle);
            //Cargar archivo por FTP.
            $this->transaccionFTP( $local, $archivo,$path);
         }catch(Exception $e){
            return 'Error al generar el archivo : InvBonificacion'.$codigoProveedor.'.txt' ;
         }   
         unset($inventario,$texto);
         return 'Archivo generado  : InvBonificacion'.$codigoProveedor.'.txt' ;
    }

    /**
    * Acción que genera el archivo plano con información de kits para el proveedor seleccionado.
    * @param $codigoProveedor
    * @return array con el estado de la transacción y el nombre del archivo.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    */
    public function kits($path,$codigoProveedor,$dir){

        //Recuperar todo los kits disponibles asociados al código del proveedor.
        $emsp=$this->container->get('Doctrine')->getManager('sipproveedores');
        
        $inventario=$emsp->createQueryBuilder()
        ->select('ik.centro,ik.codigo,ik.nombre,ik.tiempo,ik.descripcion')
        ->from('SipproveedoresEntity:InventarioKits','ik')
        ->where('ik.codCopiProveedor=?1')
        ->setParameter(1,$codigoProveedor)
        ->orderBy('ik.centro','DESC')
        ->getQuery()
        ->getResult();
        //dump($inventario);exit;
        try{
            //generar archivo txt
            $archivo ='Kits_'.$codigoProveedor.'.txt';
            $local=$dir.$archivo;
            if (file_exists($local)){
                unlink($local);//eliminar el archivo
            }
            $fileHandle = fopen($local, 'w');
            $texto='';
            if ($inventario) {
                foreach ($inventario as $k => $v) {
                    $texto.= $v['centro'].';'.$v['codigo'].';'.$v['nombre'].';'.$codigoProveedor.';'.$v['descripcion'].';0;0;;;0;'.$v['tiempo']->format('Y-m-d').';'."\n\r";
                }
                fwrite($fileHandle, $texto);
            }
            fclose($fileHandle);
            //Cargar archivo por FTP.
            $this->transaccionFTP( $local, $archivo,$path);
         }catch(Exception $e){
             return 'Error al generar el archivo : Kits_'.$codigoProveedor.'.txt';
         }   

         $kits=array();
         foreach ($inventario as $k => $v) {
            $kits[$v['centro']]=$v['codigo'];
         }
        
         return array('archivo'=>'Kits_'.$codigoProveedor.'.txt','kits'=>$kits);
        
    }

    /**
    * Acción que genera el archivo plano con información de kits para el proveedor seleccionado.
    * @param $codigoProveedor,$kits =array con los kits recuperados.
    * @return array con el estado de la transacción y el nombre del archivo.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    */
    public function kitsDetalle($path,$codigoProveedor,$kits,$dir){
        $texto='';
        $detalleKit='';
        try{
            if($kits){
                //generar archivo txt
                $archivo ='DetalleKits_'.$codigoProveedor.'.txt';
                $local=$dir.$archivo;
                if (file_exists($local)){
                    unlink($local);//eliminar el archivo
                }
                $fileHandle = fopen($local, 'w');
               
                //Recuperar los detalles de los kits disponibles asociados al código del proveedor.
                $emsp=$this->container->get('Doctrine')->getManager('sipproveedores');
                foreach ($kits as $k => $v) {
                   
                    $detalleKit=$emsp->createQueryBuilder()
                    ->select('dk')
                    ->from('SipproveedoresEntity:DetalleKits','dk')
                    ->where('dk.codigoKit=?1 AND dk.centro=?2')
                    ->setParameter(1,$v)
                    ->setParameter(2,$k)
                    ->getQuery()
                    ->getResult();
                    if($detalleKit){
                        foreach ($detalleKit as $k => $v) {
                            $texto.= ';;;;;' . $v->getDescripcion(). ';' .$v->getCantidad(). ';;' . $v->getNombreProducto().';'. $v->getCampo2().';'.$v->getCampo2(). "\n\r";
                        }
                        fwrite($fileHandle, $texto);
                    }
                }
                fclose($fileHandle);
                //Cargar archivo por FTP.
                $this->transaccionFTP($local, $archivo,$path);
            }     
        }catch(Exception $e){
            return 'Error al generar el archivo : DetalleKits_'.$codigoProveedor.'.txt';
        }
        unset($kits,$texto,$detalleKit);
        return 'Archivo generado : DetalleKits_'.$codigoProveedor.'.txt';

        
    }

    function bloqueo($elemento){
        /*$convencion = array(
            '02' => 'Mora en el pago',
            '04' => 'Cheque devuelto',
            '05' => 'Ahorro y Cr\u00e9dito',
            '07' => 'Venta droguer\u00eda',
            '08' => 'Otros motivos',
            '09' => 'Fallecimiento',
            '10' => 'Jur\u00eddico',
            '12' => 'Error Direcci\u00f3n Contacto',
            '13' => 'Venta Drog sin Reportar',
            '14' => 'Mora Oblig Traslado',
            '50' => 'Suspendida',
            '52' => 'Retirada',
            '53' => 'Retirada Fallecido',
            '54' => 'Retirada Traslados',
            '55' => 'Retirada Exclusiones',
            '56' => 'Retirada Forzoso');

        if(in_array($elemento,$convencion))
            return $convencion[$elemento];
        else
            return '';
        */

        $resultado = '';
        switch($elemento){
            case '02':
                $resultado = 'Mora en el pago';
            break;
            case '04':
                $resultado = 'Cheque devuelto';
            break;
            case '05':
                $resultado = 'Ahorro y Credito';
            break;
            case '07':
                $resultado = 'Venta drogueria';
            break;
            case '08':
                $resultado = 'Otros motivos';
            break;
            case '09':
                $resultado = 'Fallecimiento';
            break;
            case '10':
                $resultado = 'Juridico';
            break;
            case '12':
                $resultado = 'Error Direccion Contacto';
            break;
            case '13':
                $resultado = 'Venta Drog sin Reportar';
            break;
            case '14':
                $resultado = 'Mora Oblig Traslado';
            break;
            case '50':
                $resultado = 'Suspendida';
            break;
            case '52':
                $resultado = 'Retirada';
            break;
            case '53':
                $resultado = 'Retirada Fallecido';
            break;
            case '54':
                $resultado = 'Retirada Traslados';
            break;
            case '55':
                $resultado = 'Retirada Exclusiones';
            break;
            case '56':
                $resultado = 'Retirada Forzoso';
            break;
            default:
                $resultado = '';
            break;
        }

        return $resultado;
    }

    /**
    * Acción que genera el archivo plano con la información de los asociados disponibles.
    * @param $nitProveedor. $path, ruta local del archivo, a la vez la ruta en la que se guarda en el directorio remoto.
    * @return array con el estado de la transacción y el nombre del archivo.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    */
    public function asociadoConvenios($nitProveedor,$path,$dir){

        $control=false;
        try{
            //Recuperar todo el inventario disponible asociado al código del proveedor.
            $emc=$this->container->get('Doctrine')->getManager('sipasociados');
            $clientes=$emc->createQueryBuilder()
            ->select('c.codigo,c.drogueria,c.ciudad,c.depto,c.direcion,c.telefono,c.asociado,c.centro,c.ruta,c.bloqueoD,c.pCarga')
            ->from('FTPAdministradorBundle:Cliente','c')
            ->getQuery()
            ->getArrayResult();       
            //generar archivo txt
            $archivo = $dir."Asociados_Conv_" . $nitProveedor . ".txt";
            if (file_exists($archivo)){
                unlink($archivo);//eliminar el archivo
            }

            if($fileHandle = @fopen($archivo,'wb')){
                $texto="Solicitante;Nombre Solicitante;Ciudad;Departamento;Direccion;Telefono;OF.VE;Asociado;Ruta;Puesto de carga;Bloqueo;Desc Bloqueo \n";
                if ($clientes) {
                    foreach ($clientes as $k => $v) {
                        $texto.= $v['codigo'] . ';' . $v['drogueria'] .';'.$v['ciudad'].';'.$v['depto'] .';'.$v['direcion'].';'.$v['telefono'].';'.$v['centro'].';'.$v['asociado'].';'.$v['ruta'].';'.$v['pCarga'].';'.$v['bloqueoD'].';'.$this->bloqueo((string)$v['bloqueoD']) . "\n";
                    }
                    @fwrite($fileHandle, $texto);
                }
                @fclose($fileHandle);
                unset($clientes);
                //Cargar archivo por FTP.
                $control=($this->transaccionFTP($archivo ,"Asociados_Conv_".$nitProveedor.".txt",$path))?true:false;
            }else{
                $control=false;
            }
        }catch(Exception $e){
            $control=false;
        }   

        return array('estado' => $control,'archivo'=>"Asociados_Conv_".$nitProveedor.".txt" );
    }

    /**
    * Acción que envía el archivo, pasado como parámetro, vía FTP al servidor remoto especificado.
    * @param $local= ruta y nombre del archivo en el servidor local,$remoto=nombre del archivo en el servidor remoto,$path=ruta de servidor remoto.
    * @return boolean
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    */
    public function transaccionFTP($local,$remoto,$path){

        $date=new \DateTime('now');
        $date=$date->format('Y-m-d H:i:s');
        $texto=true;
        $aux='';
        $estado=false;

        //abrir la conexión FTP.
        $conexionFTP=@ftp_connect($this->container->getParameter('host_ftp'),$this->container->getParameter('port'));
        $texto=($conexionFTP)?true:'Error al tratar de abrir la conexión. Porfavor verifique el host y puerto especificados.';
        if(is_bool($texto)){
            $aux=@ftp_login($conexionFTP,$this->container->getParameter('user_ftp'),$this->container->getParameter('pass_ftp'));
            $texto=$aux?true:'Error al iniciar la sesión FTP. Porfavor verifique el usuario y la contraseña.';
            if(is_bool($texto)){
                ftp_pasv($conexionFTP, true);
                
                //Cambiamos al directorio, donde queremos subir los archivos.
                $aux= @ftp_chdir($conexionFTP,$path);
                $texto=$aux?true:'Error al buscar el directorio FTP. Verifique la ruta: '.$path;
                
                if(is_bool($texto)){
                    //Asociar la ruta al archivo recuperado.
                    @ftp_delete($conexionFTP, $remoto);
                    $texto=(@ftp_put($conexionFTP, $remoto, $local, FTP_BINARY))?'Archivo  ['.$remoto.'] cargado al servidor FTP.Destino['.$path.']':'Error al cargar el archivo '.$remoto.' al servidor FTP.';
                    $estado=true;
                    // cerrar la conexión ftp
                    ftp_close($conexionFTP);
                }else{
                    $estado=false;
                }               
            }else{
                $estado=false;
            }
            
        }else{
            $estado=false;    
        }
        //eliminar el archivo de la carpeta local.
        //if($estado)
         @unlink($local);
      
        return $texto;
        
    }

    public function transaccionFTPNovedad($local,$remoto,$path){

        $date=new \DateTime('now');
        $date=$date->format('Y-m-d H:i:s');
        $texto=true;
        $aux='';
        $estado=false;

        //abrir la conexión FTP.
        $conexionFTP=@ftp_connect($this->container->getParameter('host_ftp'),$this->container->getParameter('port'));
        $texto=($conexionFTP)?true:'Error al tratar de abrir la conexión. Porfavor verifique el host y puerto especificados.';
        if(is_bool($texto)){
            $aux=@ftp_login($conexionFTP,$this->container->getParameter('user_ftp'),$this->container->getParameter('pass_ftp'));
            $texto=$aux?true:'Error al iniciar la sesión FTP. Porfavor verifique el usuario y la contraseña.';
            if(is_bool($texto)){
                ftp_pasv($conexionFTP, true);
                
                //Cambiamos al directorio, donde queremos subir los archivos.
                $aux= @ftp_chdir($conexionFTP,$path);
                $texto=$aux?true:'Error al buscar el directorio FTP. Verifique la ruta: '.$path;
                
                if(is_bool($texto)){
                    //Asociar la ruta al archivo recuperado.
                    //@ftp_delete($conexionFTP, $remoto);
                    $texto=(@ftp_put($conexionFTP, $remoto, $local, FTP_BINARY))?'Archivo  ['.$remoto.'] cargado al servidor FTP.Destino['.$path.']':'Error al cargar el archivo '.$remoto.' al servidor FTP.';
                    $estado=true;
                    // cerrar la conexión ftp
                    ftp_close($conexionFTP);
                }else{
                    $estado=false;
                }               
            }else{
                $estado=false;
            }
            
        }else{
            $estado=false;    
        }
        
    }


    /**
    * Acción que genera el archivo log con el informe final de la carga de los pedidos
    * @param $informe=array() con la información acerca del estado de la carga de pedidos,$local ruta local donde se creará el archivo.
    * @return array con el estado de la transacción y el nombre del archivo.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    */
    public function generarLog($informe,$path, $local, $logRespuesta, $nombreArchivo){
        $control=false;
        $archivo = $local.'Reporte_'.$nombreArchivo.'_'.date("dmYHis").'.txt';
        if (file_exists($archivo)){
            unlink($archivo);//eliminar el archivo
        }
        
        $date = new \DateTime('now');
        $date=$date->format('Y-m-d H:i:s');

        $fileHandle = @fopen($archivo, 'wb');
        $texto='';
       // dump($informe);exit();

        foreach($logRespuesta as $respuesta){
            $texto.= $respuesta. "\r\n";
        }

        
        @fwrite($fileHandle, $texto);
        @fclose($fileHandle);
        unset($informe);
       
        //Cargar archivo por FTP.
        $aux=$this->transaccionFTP($archivo ,'Reporte_'.$nombreArchivo.'_'.date("dmYHis").'.txt',$path.'procesados/');

        //@unlink($local.'log.txt');

        return $aux;
        
    }

    /**
    * Acción que genera el archivo log con el informe final de la carga de los pedidos SIPPROVEEDORES
    * @param $archivo= nombre del archivo local  $informe=array() con la información acerca del estado de la carga de pedidos,$path ruta del directorio remoto.
    * @return array con el estado de la transacción y el nombre del archivo.
    * @author Julián casas <j.casas@waplicaciones.com.co>
    * @since 2.6
    */
    public function logSipProveedores($archivo,$informe,$path,$dir){

        $control=false;
        $date = new \DateTime('now');
        $date=$date->format('Y-m-d H:i:s');
        $log= '__log__'.$archivo;
        $local =$dir.$log;
        $fileHandle = @fopen($local, 'wb');

        $texto='';
        $consecutivos=false;
        $datosPedido=false;
        $insertados=false;
        $noLeidas=false;
        $texto.=PHP_EOL.'ARCHIVO ['.$archivo.']'.PHP_EOL;  
        $texto.='-------------------------------------------------------------------------------------'.PHP_EOL;
        $texto.='| FECHA      = '.$date.'            '.PHP_EOL;
        if(isset($informe['errorArchivo'])){
            $texto.='  -------------------------------------------------------------------------------------'.PHP_EOL;
            $texto.='|  '.$informe['errorArchivo'].PHP_EOL;
            $texto.='  -------------------------------------------------------------------------------------'.PHP_EOL;
        }else{
           foreach ($informe as $key => $value) {
                if($key=='consecutivos'){
                    $consecutivos=$value;
                }
                if($key=='datosPedido'){
                    $datosPedido=$value;
                }
                if($key=='insertados'){
                    $insertados=$value;
                }
                if($key=='noLeidas'){
                    $noLeidas=$value;
                }
            }
            unset($informe);
            if($consecutivos){
                foreach ($consecutivos as $codigoCopi => $consecutivo) {
                   $texto.='  ---------------------------- PEDIDO : '.$consecutivo.PHP_EOL;
                   $texto.='| DROGUERÍA  = '.$datosPedido[$consecutivo]['drogueria']. PHP_EOL;
                   $texto.='| CÓDIGO  = '.$codigoCopi. PHP_EOL;
                   $texto.='| TOTAL DEL PEDIDO  = '.number_format($datosPedido[$consecutivo]['totalPedido'], 0, '', '.'). PHP_EOL;
                   $texto.='-------------------------------------------------------------------------------------'.PHP_EOL;
                }
            }else{
                    $texto.='--------------------------------------------------------------------------------------- '.PHP_EOL;
                    foreach ($noLeidas as $value) {
                          $texto.=$value.PHP_EOL;
                    }   
                    $texto.='-------------------------------------------------------------------------------------'.PHP_EOL;
            }     
        }    



        @fwrite($fileHandle,$texto);
        @fclose($fileHandle);

        //Cargar archivo por FTP.
        $aux=$this->transaccionFTP($local ,$log ,$path);
        return $aux;
    }


    public function inventarioConvenios($path,$idProveedor,$codigoProveedor,$dir){

        $emc=$this->container->get('Doctrine')->getManager('convenios');

        //Recuperar todo el inventario disponible asociado al código del proveedor.
        
        $inventario = $emc->createQuery("SELECT i.codigo, i.codigoBarras, i.descripcion, i.presentacion, i.precio,  i.precioReal, i.iva, i.descuento FROM FTPAdministradorBundle:InventarioProducto i WHERE i.proveedor =:idProveedor ")
                ->setParameter('idProveedor', $idProveedor)->getResult();
        
        $texto='';

        try{
            //generar archivo txt
            $archivo ='InvCon'.$codigoProveedor.'.txt';
            $local=$dir.$archivo;
            if (file_exists($local)){
                @unlink($local);//eliminar el archivo
            }

            $fileHandle = @fopen($local, 'w');
            
            
            if ($inventario) {
                foreach ($inventario as $k => $v) {
                    
                    $texto.= $v['codigo'].';'.$v['codigoBarras'].';'.$v['descripcion'].';'.$v['presentacion'].';'.$v['precio'].';0;'.$v['precioReal'].';'.$v['iva'].';'.$v['descuento'].';'."\n\r";
                    
                }
                fwrite($fileHandle, $texto);
            }
            fclose($fileHandle);
            //Cargar archivo por FTP.
            $this->transaccionFTP($local, $archivo,$path.'inventario/');
        }catch(Exception $e){
            return ' Error al generar el inventario : InvCon'.$codigoProveedor.'.txt';
        }   
        unset($inventario, $texto);
        return ' Archivo generado : InvCon'.$codigoProveedor.'.txt';
        
    }

 
}//fin class

?>
