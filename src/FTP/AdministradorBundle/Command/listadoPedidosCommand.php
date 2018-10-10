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

class listadoPedidosCommand extends ContainerAwareCommand{

    private $message='';

    protected function configure(){
        parent::configure();
        $this->setName('listadoPedidos:convenios')->setDescription('lista los pedidos realizados para convenios');
    }
    protected function execute(InputInterface $input, OutputInterface $output){
        exit('mantenimiento listadoPedidos:convenios');
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $bandera=true;

        $output->writeln("Iniciando...");

        $fechaArchivo = date('YmdHis');
        $fechaEjecucion = new \DateTime();
        $fechaFinal = $fechaEjecucion->format('Y-m-d H:i:s');
        $output->writeln($fechaFinal);

        $fechaEjecucion->modify('-1 hour '); 
        $fechaInicial =  $fechaEjecucion->format('Y-m-d H:i:s');
        $output->writeln($fechaInicial);

        //exit();



        
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


        


        //Generar pedido.
        foreach($proveedores as $proveedor){

            //se abre conexion ftp
            $conexionFTP=@ftp_connect($this->getContainer()->getParameter('host_ftp'),$this->getContainer()->getParameter('port'));
            @ftp_login($conexionFTP,$this->getContainer()->getParameter('user_ftp'),$this->getContainer()->getParameter('pass_ftp'));

            
            $output->writeln("se procesa el proveedor ".$proveedor['proveedor']);

            $path = $proveedor['carpetaConvenios']."/";

            if(!@ftp_chdir($conexionFTP,$path)){
                $output->writeln("no puede cambiar de directorio ".$path);
                $directorio = false;
            }else{
                $output->writeln("se cambia a directorio ".$path);
                if(@ftp_chdir($conexionFTP,$path.'pedidos/') == false){
                    @ftp_mkdir($conexionFTP,'pedidos');
                    $directorio = true;
                }else{
                    $directorio = true;
                }
            }

            //$directorio = true;
            if($directorio){

                $pedidos = $emc->createQuery("SELECT p.fecha, p.consecutivo, p.codigoDrogueria, t.id AS transferencistaId, t.nombres, dp.productoCodigo, dp.productoDescripcion, dp.productoCodigoBarras, dp.cantidadPedida, p.remision
                    FROM FTPAdministradorBundle:Pedido p 
                    JOIN FTPAdministradorBundle:PedidoDescripcion dp WITH dp.pedido=p.id 
                    LEFT JOIN FTPAdministradorBundle:Transferencista t WITH dp.transferencista = t.id
                    WHERE p.proveedor =".$arrayProveedoresConvenios[$proveedor['nit']]." AND p.fecha >='".$fechaInicial."' AND p.fecha <='".$fechaFinal."' ORDER BY p.fecha DESC ")->getResult();


                //dump($pedidos);exit();
                if($pedidos){
                    $output->writeln("renderizando los productos");
                    $template= $this->getContainer()->get('templating')->render('FTPAdministradorBundle:Proveedor:pedidosRealizadosCsv.html.twig',array('pedidos'=>$pedidos,'cliente'=>$dataClientes));

                     $nombreArchivo="pedidosRealizados".$fechaArchivo.".csv";
                     $archivo = fopen($dir.$nombreArchivo,'w');

                    fwrite ($archivo,$template);
                    fclose ($archivo);


                    $output->writeln("moviendo el archivo a ".$nombreArchivo,$path.'pedidos/');

                    $tarea->transaccionFTP($dir.$nombreArchivo, $nombreArchivo,$path.'pedidos/');
                    //@unlink($ruta.$nombreArchivo);

                    $archivoRespaldo = fopen($dir.'convenios/vogue/'.$nombreArchivo,'w');
                    fwrite ($archivoRespaldo,$template);
                    fclose ($archivoRespaldo);
                }else{
                    $output->writeln("no tiene pedidos ");
                }
                


                

            }else{
                $output->writeln("no tiene directorio ");
            }

            ftp_close($conexionFTP);
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
