<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogProveedores
 *
 * @ORM\Table(name="log_proveedores", indexes={@ORM\Index(name="id_proveedor_idx", columns={"proveedor_codigo_copi"}), @ORM\Index(name="log_archivo", columns={"log_archivo"}), @ORM\Index(name="log_admin", columns={"log_admin"})})
 * @ORM\Entity
 */
class LogProveedores
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
     * @ORM\Column(name="codigo_drogueria", type="string", length=10, nullable=false)
     */
    private $codigoDrogueria;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_pedido", type="string", length=50, nullable=true)
     */
    private $codigoPedido;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_pedido", type="integer", nullable=false)
     */
    private $totalPedido;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_productos", type="integer", nullable=false)
     */
    private $numProductos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_confirmado", type="datetime", nullable=true)
     */
    private $fechaConfirmado;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor_codigo_copi", type="string", length=30, nullable=false)
     */
    private $proveedorCodigoCopi;

    /**
     * @var integer
     *
     * @ORM\Column(name="log_archivo", type="bigint", nullable=true)
     */
    private $logArchivo;

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
     * Set codigoDrogueria
     *
     * @param string $codigoDrogueria
     * @return LogProveedores
     */
    public function setCodigoDrogueria($codigoDrogueria)
    {
        $this->codigoDrogueria = $codigoDrogueria;
    
        return $this;
    }

    /**
     * Get codigoDrogueria
     *
     * @return string 
     */
    public function getCodigoDrogueria()
    {
        return $this->codigoDrogueria;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return LogProveedores
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
     * Set codigoPedido
     *
     * @param string $codigoPedido
     * @return LogProveedores
     */
    public function setCodigoPedido($codigoPedido)
    {
        $this->codigoPedido = $codigoPedido;
    
        return $this;
    }

    /**
     * Get codigoPedido
     *
     * @return string 
     */
    public function getCodigoPedido()
    {
        return $this->codigoPedido;
    }

    /**
     * Set totalPedido
     *
     * @param integer $totalPedido
     * @return LogProveedores
     */
    public function setTotalPedido($totalPedido)
    {
        $this->totalPedido = $totalPedido;
    
        return $this;
    }

    /**
     * Get totalPedido
     *
     * @return integer 
     */
    public function getTotalPedido()
    {
        return $this->totalPedido;
    }

    /**
     * Set numProductos
     *
     * @param integer $numProductos
     * @return LogProveedores
     */
    public function setNumProductos($numProductos)
    {
        $this->numProductos = $numProductos;
    
        return $this;
    }

    /**
     * Get numProductos
     *
     * @return integer 
     */
    public function getNumProductos()
    {
        return $this->numProductos;
    }

    /**
     * Set fechaConfirmado
     *
     * @param \DateTime $fechaConfirmado
     * @return LogProveedores
     */
    public function setFechaConfirmado($fechaConfirmado)
    {
        $this->fechaConfirmado = $fechaConfirmado;
    
        return $this;
    }

    /**
     * Get fechaConfirmado
     *
     * @return \DateTime 
     */
    public function getFechaConfirmado()
    {
        return $this->fechaConfirmado;
    }

    /**
     * Set proveedorCodigoCopi
     *
     * @param string $proveedorCodigoCopi
     * @return LogProveedores
     */
    public function setProveedorCodigoCopi($proveedorCodigoCopi)
    {
        $this->proveedorCodigoCopi = $proveedorCodigoCopi;
    
        return $this;
    }

    /**
     * Get proveedorCodigoCopi
     *
     * @return string 
     */
    public function getProveedorCodigoCopi()
    {
        return $this->proveedorCodigoCopi;
    }

    /**
     * Set logArchivo
     *
     * @param integer $logArchivo
     * @return LogProveedores
     */
    public function setLogArchivo($logArchivo)
    {
        $this->logArchivo = $logArchivo;
    
        return $this;
    }

    /**
     * Get logArchivo
     *
     * @return integer 
     */
    public function getLogArchivo()
    {
        return $this->logArchivo;
    }

    /**
     * Set logAdmin
     *
     * @param integer $logAdmin
     * @return LogProveedores
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
