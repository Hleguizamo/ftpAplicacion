<?php
namespace FTP\AdministradorBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use FTP\AdministradorBundle\Entity\LogAdministrador;

use FTP\AdministradorBundle\Entity\sipproveedores\Proveedor;
use FTP\AdministradorBundle\Entity\sipproveedores\Transferencista;
use FTP\AdministradorBundle\Entity\sipproveedores\Inventario;
use FTP\AdministradorBundle\Entity\sipproveedores\InventarioKits;
use FTP\AdministradorBundle\Entity\sipproveedores\DescripcionPedido;
use FTP\AdministradorBundle\Entity\sipproveedores\Pedidos;
use FTP\AdministradorBundle\Entity\sipproveedores\InventarioPrepacks;
 
class SipproveedoresPedidosCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        parent::configure();
        $this->setName('sipproveedores:pedidos')
        ->setDescription('para procesar y cargar pedididos a sipproveedores.');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        exit('mantenimiento sipproveedores:pedidos');  
        set_time_limit(0);
        
        ini_set('memory_limit', '2048M');

        $sipproveedores = $this->getContainer()->get('procesarArchivos');
        //para llamar las funciones que se neseciten 
        $sipproveedores->inicioCargaProveedores(false);

        $output->writeln('Fin de la tarea');
    }

/*
     // Si la columna LOTE esta vacia no se evalua la columna LOTE en la consulta.
    $lote=($datos[4])?' AND i.lote=:lote ':'';
    $query=$emsp->createQuery('SELECT i FROM SipproveedoresEntity:Inventario i WHERE i.precioReal IN '
        .'( SELECT MIN(ia.precioReal) FROM SipproveedoresEntity:Inventario ia WHERE ia.centro=:centro AND ia.material=:material AND i.proveedorId=:proveedor '.$lote.') '
        .' AND i.centro=:centro AND i.material=:material AND i.proveedorId=:proveedor '.$lote);
    $query->setParameter('centro',$datos[6]);
    $query->setParameter('material',$datos[2]);
    $query->setParameter('proveedor',$transferencistas[$datos[0]][1]);
    $query->setMaxResults(1);
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
        }
*/

}//end class

?>
