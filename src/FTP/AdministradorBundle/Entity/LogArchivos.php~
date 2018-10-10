<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogArchivos
 *
 * @ORM\Table(name="log_archivos")
 * @ORM\Entity
 */
class LogArchivos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="archivo", type="string", length=100, nullable=true)
     */
    private $archivo;

    /**
     * @var integer
     *
     * @ORM\Column(name="peso_mb", type="integer", nullable=true)
     */
    private $pesoMb;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor", type="string", length=100, nullable=true)
     */
    private $proveedor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_procesado", type="datetime", nullable=true)
     */
    private $fechaProcesado;

    /**
     * @var integer
     *
     * @ORM\Column(name="log_admin", type="bigint", nullable=true)
     */
    private $logAdmin;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set archivo
     *
     * @param string $archivo
     * @return LogArchivos
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;
    
        return $this;
    }

    /**
     * Get archivo
     *
     * @return string 
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set pesoMb
     *
     * @param integer $pesoMb
     * @return LogArchivos
     */
    public function setPesoMb($pesoMb)
    {
        $this->pesoMb = $pesoMb;
    
        return $this;
    }

    /**
     * Get pesoMb
     *
     * @return integer 
     */
    public function getPesoMb()
    {
        return $this->pesoMb;
    }

    /**
     * Set proveedor
     *
     * @param string $proveedor
     * @return LogArchivos
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return string 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set fechaProcesado
     *
     * @param \DateTime $fechaProcesado
     * @return LogArchivos
     */
    public function setFechaProcesado($fechaProcesado)
    {
        $this->fechaProcesado = $fechaProcesado;
    
        return $this;
    }

    /**
     * Get fechaProcesado
     *
     * @return \DateTime 
     */
    public function getFechaProcesado()
    {
        return $this->fechaProcesado;
    }

    /**
     * Set logAdmin
     *
     * @param integer $logAdmin
     * @return LogArchivos
     */
    public function setLogAdmin($logAdmin)
    {
        $this->logAdmin = $logAdmin;
    
        return $this;
    }

    /**
     * Get logAdmin
     *
     * @return integer 
     */
    public function getLogAdmin()
    {
        return $this->logAdmin;
    }
}
