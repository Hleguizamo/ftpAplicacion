<?php 
namespace FTP\AdministradorBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;

class WAExtension extends \Twig_Extension
{
    public function getFilters()
    {
        //return new \Twig_SimpleFilter('fechaLiquidacion', array($this, 'fechaLiquidacion'));
        return array(
            new \Twig_SimpleFilter('filtroFecha', array($this, 'filtroFecha'))
        );
    }
    
    public function filtroFecha($filtroFecha)
    {
        if($filtroFecha == "0000-00-00" || $filtroFecha == "-0001-11-30" || $filtroFecha == "-0001-11-30 00:00:00"){
            $fecha = "";
        }else{

            if(strtotime($filtroFecha) === false || strtotime($filtroFecha) === -1 ){
                $fecha = "";
            }else{
                $fecha = $filtroFecha;
            }
        }
        return $fecha;
    }

    
    public function getName()
    {
        return 'wa_extension';
    }
}