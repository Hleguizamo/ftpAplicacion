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

class procesarInventarioConveniosCommand extends ContainerAwareCommand{
    protected function configure(){
        parent::configure();
        $this->setName('procesarInventario:convenios')->setDescription('Procesa el listado de productos para convenios');
    }
    protected function execute(InputInterface $input, OutputInterface $output){
        exit('mantenimiento procesarInventario:convenios');
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        $emc = $this->getContainer()->get('doctrine')->getManager('convenios');
        
        $tarea = $this->getContainer()->get('generarArchivos');
        
        $dir = $this->getContainer()->get('kernel')->getRootDir().'/../web/';
        
        
        $proveeresFtp = $em->createQuery("SELECT pr.estadoConvenios, pr.nitProveedor, pr.carpetaConvenios, pr.codigoProveedor FROM FTPAdministradorBundle:Proveedores pr ")->getResult();
        
        $proveedoresConvenios = $emc->getRepository('FTPAdministradorBundle:Proveedor')->findAll();
        $arrayProveedoresConvenios = array();
        foreach($proveedoresConvenios as $datosProveedor){
            $arrayProveedoresConvenios[$datosProveedor->getCodigoCopidrogas()]['idProveedor'] = $datosProveedor->getId();
            $arrayProveedoresConvenios[$datosProveedor->getCodigoCopidrogas()]['nit'] = $datosProveedor->getCodigoCopidrogas();
        }
        
        foreach($proveeresFtp as $proveedor){
            
            if($proveedor['estadoConvenios'] == 1 && isset($arrayProveedoresConvenios[$proveedor['nitProveedor']])){
                
                $asociados=$tarea->asociadoConvenios($proveedor['codigoProveedor'],$proveedor['carpetaConvenios']."/inventario/",$dir);//Generar archivo de texto plano con informaci?n de los asociados y cargarlo a la carpeta de Convenios por FTP.
                
                
                
                if($asociados['estado'] == true){
                    
                    $output->writeln("Archivo de asociados generado : ".$asociados['archivo']);
                    $this->registralog("4.2.8 Archivo de asociados generado : ".$asociados['archivo']);
                }else{
                    
                    $output->writeln("Error al generar el listado de Asociados : ".$asociados['estado']);
                    $this->registralog("4.3 Error al generar el listado de Asociados : ".$asociados['estado']);
                }
                
                
                $productos[$proveedor['nitProveedor']]=$tarea->inventarioConvenios($proveedor['carpetaConvenios']."/",$arrayProveedoresConvenios[$proveedor['nitProveedor']]['idProveedor'],$proveedor['nitProveedor'],$dir);
                
                $output->writeln($productos[$proveedor['nitProveedor']]);
                
                $this->registralog("4.3 ".$productos[$proveedor['nitProveedor']]);

                
            }
            
        }

        
        $output->writeln("Fin de la tarea.");
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
    
    
}
?>