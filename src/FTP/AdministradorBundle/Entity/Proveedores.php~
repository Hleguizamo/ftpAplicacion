<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedores
 *
 * @ORM\Table(name="proveedores")
 * @ORM\Entity
 */
class Proveedores
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor", type="string", length=150, nullable=false)
     */
    private $proveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_proveedor", type="string", length=4, nullable=false)
     */
    private $codigoProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="nit_proveedor", type="string", length=30, nullable=false)
     */
    private $nitProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="carpeta_transferencista", type="string", length=100, nullable=true)
     */
    private $carpetaTransferencista;

    /**
     * @var string
     *
     * @ORM\Column(name="carpeta_convenios", type="string", length=100, nullable=true)
     */
    private $carpetaConvenios;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado_transferencia", type="boolean", nullable=false)
     */
    private $estadoTransferencia;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado_convenios", type="boolean", nullable=false)
     */
    private $estadoConvenios;

    /**
     * @var string
     *
     * @ORM\Column(name="encargado_proveedor", type="string", length=150, nullable=false)
     */
    private $encargadoProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="email_encargado", type="string", length=100, nullable=false)
     */
    private $emailEncargado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ultimos_pedidos", type="datetime", nullable=true)
     */
    private $ultimosPedidos;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ult_cargue_transferencista", type="datetime", nullable=true)
     */
    private $ultCargueTransferencista;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ult_cargue_convenios", type="datetime", nullable=true)
     */
    private $ultCargueConvenios;



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
     * Set proveedor
     *
     * @param string $proveedor
     * @return Proveedores
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
     * Set codigoProveedor
     *
     * @param string $codigoProveedor
     * @return Proveedores
     */
    public function setCodigoProveedor($codigoProveedor)
    {
        $this->codigoProveedor = $codigoProveedor;
    
        return $this;
    }

    /**
     * Get codigoProveedor
     *
     * @return string 
     */
    public function getCodigoProveedor()
    {
        return $this->codigoProveedor;
    }

    /**
     * Set nitProveedor
     *
     * @param string $nitProveedor
     * @return Proveedores
     */
    public function setNitProveedor($nitProveedor)
    {
        $this->nitProveedor = $nitProveedor;
    
        return $this;
    }

    /**
     * Get nitProveedor
     *
     * @return string 
     */
    public function getNitProveedor()
    {
        return $this->nitProveedor;
    }

    /**
     * Set carpetaTransferencista
     *
     * @param string $carpetaTransferencista
     * @return Proveedores
     */
    public function setCarpetaTransferencista($carpetaTransferencista)
    {
        $this->carpetaTransferencista = $carpetaTransferencista;
    
        return $this;
    }

    /**
     * Get carpetaTransferencista
     *
     * @return string 
     */
    public function getCarpetaTransferencista()
    {
        return $this->carpetaTransferencista;
    }

    /**
     * Set carpetaConvenios
     *
     * @param string $carpetaConvenios
     * @return Proveedores
     */
    public function setCarpetaConvenios($carpetaConvenios)
    {
        $this->carpetaConvenios = $carpetaConvenios;
    
        return $this;
    }

    /**
     * Get carpetaConvenios
     *
     * @return string 
     */
    public function getCarpetaConvenios()
    {
        return $this->carpetaConvenios;
    }

    /**
     * Set estadoTransferencia
     *
     * @param boolean $estadoTransferencia
     * @return Proveedores
     */
    public function setEstadoTransferencia($estadoTransferencia)
    {
        $this->estadoTransferencia = $estadoTransferencia;
    
        return $this;
    }

    /**
     * Get estadoTransferencia
     *
     * @return boolean 
     */
    public function getEstadoTransferencia()
    {
        return $this->estadoTransferencia;
    }

    /**
     * Set estadoConvenios
     *
     * @param boolean $estadoConvenios
     * @return Proveedores
     */
    public function setEstadoConvenios($estadoConvenios)
    {
        $this->estadoConvenios = $estadoConvenios;
    
        return $this;
    }

    /**
     * Get estadoConvenios
     *
     * @return boolean 
     */
    public function getEstadoConvenios()
    {
        return $this->estadoConvenios;
    }

    /**
     * Set encargadoProveedor
     *
     * @param string $encargadoProveedor
     * @return Proveedores
     */
    public function setEncargadoProveedor($encargadoProveedor)
    {
        $this->encargadoProveedor = $encargadoProveedor;
    
        return $this;
    }

    /**
     * Get encargadoProveedor
     *
     * @return string 
     */
    public function getEncargadoProveedor()
    {
        return $this->encargadoProveedor;
    }

    /**
     * Set emailEncargado
     *
     * @param string $emailEncargado
     * @return Proveedores
     */
    public function setEmailEncargado($emailEncargado)
    {
        $this->emailEncargado = $emailEncargado;
    
        return $this;
    }

    /**
     * Get emailEncargado
     *
     * @return string 
     */
    public function getEmailEncargado()
    {
        return $this->emailEncargado;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return Proveedores
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set ultimosPedidos
     *
     * @param \DateTime $ultimosPedidos
     * @return Proveedores
     */
    public function setUltimosPedidos($ultimosPedidos)
    {
        $this->ultimosPedidos = $ultimosPedidos;
    
        return $this;
    }

    /**
     * Get ultimosPedidos
     *
     * @return \DateTime 
     */
    public function getUltimosPedidos()
    {
        return $this->ultimosPedidos;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Proveedores
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return integer 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set ultCargueTransferencista
     *
     * @param \DateTime $ultCargueTransferencista
     * @return Proveedores
     */
    public function setUltCargueTransferencista($ultCargueTransferencista)
    {
        $this->ultCargueTransferencista = $ultCargueTransferencista;
    
        return $this;
    }

    /**
     * Get ultCargueTransferencista
     *
     * @return \DateTime 
     */
    public function getUltCargueTransferencista()
    {
        return $this->ultCargueTransferencista;
    }

    /**
     * Set ultCargueConvenios
     *
     * @param \DateTime $ultCargueConvenios
     * @return Proveedores
     */
    public function setUltCargueConvenios($ultCargueConvenios)
    {
        $this->ultCargueConvenios = $ultCargueConvenios;
    
        return $this;
    }

    /**
     * Get ultCargueConvenios
     *
     * @return \DateTime 
     */
    public function getUltCargueConvenios()
    {
        return $this->ultCargueConvenios;
    }
}
