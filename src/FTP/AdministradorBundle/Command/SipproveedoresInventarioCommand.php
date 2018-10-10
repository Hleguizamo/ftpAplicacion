<?php
namespace FTP\AdministradorBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
 
class SipproveedoresInventarioCommand extends ContainerAwareCommand
{
    protected function configure(){
        parent::configure();
        $this->setName('sipproveedores:inventario')
        ->setDescription('Cargar el inventario en SipProveedores para todos los proveedores habilitados.');
    }

    protected function execute(InputInterface $input, OutputInterface $output){   
        exit('mantenimiento sipproveedores:inventario');    
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $this->inventario($output);

        $this->cargarArchivoDroguerias($output);

        $this->cargarArchivoProductosPrecio($output);

        $output->writeln('Fin de la tarea');
    }



    public function inventario(OutputInterface $output){
        $output->writeln('Iniciando tarea...');
        $session=null;
        $dir=$this->getContainer()->get('kernel')->getRootDir().'/../web/';
        $tarea=$this->getContainer()->get('generarArchivos');

        $em=$this->getContainer()->get('doctrine')->getManager();
        $emsp=$this->getContainer()->get('doctrine')->getManager('sipproveedores');

         //Recuperar proveedores.
        $proveedores=$em->createQueryBuilder()
        ->select('p.proveedor,p.codigoProveedor,p.estadoTransferencia,p.carpetaTransferencista')
        ->from('FTPAdministradorBundle:Proveedores','p')
        ->getQuery()
        ->getArrayResult();

        $proveedores=$this->estadoSipProveedores($proveedores);
        if(isset($proveedores['habilitados'])){

            //conexion a FTP.
            $conexionFTP=@ftp_connect($this->getContainer()->getParameter('host_ftp'),$this->getContainer()->getParameter('port'));
            @ftp_login($conexionFTP,$this->getContainer()->getParameter('user_ftp'),$this->getContainer()->getParameter('pass_ftp'));

            foreach ($proveedores['habilitados'] as $codigoProveedor=>$proveedor) {
                $output->writeln('**********Procesando proveedor '.$proveedor['proveedor'].'**********');
                if($proveedor['carpeta']){
                    //Validamos si el directorio existe
                    $output->writeln('Buscando carpeta');
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
                            @ftp_chdir($conexionFTP,'inventario');
                        }         
                    }else{
                        @ftp_chdir($conexionFTP,'inventario');
                    }

                    $ruta=ftp_pwd($conexionFTP);
                    $mensaje='';
                    //Archivo asociados.
                    $output->writeln('Generando asociados');
                    $asociados=$tarea->asociados($ruta,$codigoProveedor,$dir);
                    $mensaje.=$asociados;
                    $output->writeln($asociados);

                    //Generar inventario
                    $output->writeln('Generando inventario');
                    $inventario=$tarea->inventario($ruta,$codigoProveedor,$dir);
                    $mensaje.=$inventario;
                    $output->writeln($inventario);

                    //Bonificaciones
                    $output->writeln('Generando bonificaciones');
                    $bonificaciones=$tarea->bonificaciones($ruta,$codigoProveedor,$dir);
                    $mensaje.=$bonificaciones;
                    $output->writeln($bonificaciones);

                    //Kits
                    $output->writeln('Generando kits');
                    $kits=$tarea->kits($ruta,$codigoProveedor,$dir);
                    $mensaje.=$kits['archivo'];
                    $output->writeln($kits['archivo']);
                    
                    //Detalle kits
                    $output->writeln('Generando detalle kits');
                    $detallekits=$tarea->kitsDetalle($ruta,$codigoProveedor,$kits['kits'],$dir);
                    $mensaje.=$detallekits;
                    $output->writeln($detallekits);
                   
                    $this->registrarInventario($mensaje,$proveedor['proveedor']);
                    @ftp_chdir($conexionFTP,'../../../');
                }else{
                   $mensaje='La carpeta para el proveedor ['.$proveedor['proveedor'].']. No se encuentra registrada en el sistema.';
                   $this->estadoCarpeta($mensaje,$codigoProveedor,$session);
                   $carpeta[]=$mensaje; 
                   $output->writeln($mensaje);
                } 
                
            }//fin foreach 
        }//fin if
        if(isset($proveedores['inhabilitados'])){
            $output->writeln('Proveedores inhabilitados');
            $proveedor='';
            foreach ($proveedores['inhabilitados'] as  $value) {
                $output->writeln($value);
                if($proveedor!='')
                    $proveedor.=',';
                $proveedor.=$value;
            }
            unset($proveedores['inhabilitados'],$value);
            $this->registralog('3.1.6 Proveedores no procesados '.$proveedor.' ('.count($proveedor).') ',null);
        }
    }
    
    /**
        * Acción que verifica el estado de los proveedores para acceder a SipProveedores y los separa en un array con índices [habilitado,deshabilitado]
        * @param $proveedores= array de proveedores recuperados por el sistema.
        * @return array con los proveedores habilitados y deshabilitados.
        * @author Julián casas <j.casas@waplicaciones.com.co>
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

    /**
        * @param Acción que registra el estado de las carpetas
        * @return json y/o código de estatus con el estado final de la tarea.
        * @author Julián casas <j.casas@waplicaciones.com.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function estadoCarpeta($mensaje,$proveedor,$session){

        $this->registralog('3.1.1'.$mensaje,$session=null);     
        unset($tarear);
    }

    /**
        * Acción registra la información del estado FTP de los archivos.
        * @param {$inventario:array con los archivos generados,$proveedor:nombre del proveedor,$session:información de la sesión actual.}
        * @author Julián casas <j.casas@waplicaciones.com.co>
        * @since 2.6
        * @category FTP\Proveedor
    */
    public function registrarInventario($mensaje,$proveedor,$session=null){
        $this->registralog('3.1.5  '.$proveedor.'. Inventario ['.$mensaje.'] ',$session);         
    }


    /**
     * Registra en la tabla de logs las 
     * actividades de los usuarios.
     * @param string $actividad Actividad que realizo el usuario
     * @param string $rol Rol del usuario
    */
    public function registralog($actividad,$id) {
        $conexion=$this->getContainer()->get('Doctrine')->getManager()->getConnection();
        $ip='';
        
        $fecha = new \DateTime('now');
        
        $sql=' INSERT INTO log_administrador VALUES(null,null,?,?,?) ';
        $aux=$conexion->prepare($sql);
        $aux->bindValue(1,$fecha->format('Y-m-d H:i:s'));
        $aux->bindParam(2,$actividad);
        $aux->bindParam(3,$ip);
        $aux->execute();

    }


    /**
     * Carga el archivo maestro_drog.txt al servidor ftp
    */
    public function cargarArchivoDroguerias(OutputInterface $output){


        $conexionFtp = ftp_connect($this->getContainer()->getParameter('host_ftp'),$this->getContainer()->getParameter('port'));
        ftp_set_option($conexionFtp, FTP_TIMEOUT_SEC, 360);
        ftp_pasv($conexionFtp, TRUE);

        $handle = fopen('/home/planos/maestro_drog.txt', "r");

        if (ftp_login($conexionFtp, $this->getContainer()->getParameter('user_ftp'),$this->getContainer()->getParameter('pass_ftp'))) {
            
            ftp_chdir($conexionFtp, '/copi');

            if (!ftp_fput($conexionFtp, 'maestro_drog.txt', $handle, FTP_BINARY, 0)) {
                
                $output->writeln('Error: Imposible descargar el archivo ->maestro_drog.txt<-');
            } 
        }else{
            $output->writeln('Error: no se pudo conectar al servidor');
        }
        ftp_close($conexionFtp);

    } 

    /**
     * Carga los archivos de precios de productos al servidor ftp
    */
    public function cargarArchivoProductosPrecio(OutputInterface $output){


        $conexionFtp = ftp_connect($this->getContainer()->getParameter('host_ftp'),$this->getContainer()->getParameter('port'));
        ftp_set_option($conexionFtp, FTP_TIMEOUT_SEC, 360);
        ftp_pasv($conexionFtp, TRUE);

        $archivos = array(
            'precios_cot' => 'precio_cot_bol.txt', 
            'precios_cal' => 'precio_cali_bol.txt', 
            'precios_med' => 'precio_med_bol.txt', 
            'precios_bar' => 'precio_bar_bol.txt', 
            'precios_per' => 'precio_per_bol.txt', 
            'precios_Bello' => 'precio_bello_bol.txt', 
            'precios_buc' => 'precio_buc_bol.txt');
        
        foreach($archivos as $k => $archivo){

            if(is_file('/home/planos/'.$archivo)){

                $handle = fopen('/home/planos/'.$archivo, "r");


                if (ftp_login($conexionFtp, $this->getContainer()->getParameter('user_ftp'),$this->getContainer()->getParameter('pass_ftp'))) {
                
                    ftp_chdir($conexionFtp, '/copi');

                    if (!ftp_fput($conexionFtp, $k.'.txt', $handle, FTP_BINARY, 0)) {
                        
                        $output->writeln('Error: Imposible descargar el archivo ->'.$archivo.'<-');
                    } 
                }else{
                    $output->writeln('Error: no se pudo conectar al servidor');
                }

            }

            

        }


    } 

    

}

?>