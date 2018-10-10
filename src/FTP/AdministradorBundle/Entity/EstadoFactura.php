<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoFactura
 *
 * @ORM\Table(name="estado_factura", indexes={@ORM\Index(name="proveedor_id", columns={"proveedor_id"})})
 * @ORM\Entity
 */
class EstadoFactura
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
     * @ORM\Column(name="numero_factura", type="string", length=30, nullable=false)
     */
    private $numeroFactura;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_remisiones", type="integer", nullable=true)
     */
    private $cantidadRemisiones;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_pedidos", type="integer", nullable=true)
     */
    private $cantidadPedidos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_confirmacion", type="datetime", nullable=true)
     */
    private $fechaConfirmacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado_procesamiento", type="integer", nullable=true)
     */
    private $estadoProcesamiento;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado_ftp", type="integer", nullable=true)
     */
    private $estadoFtp = '0';

    /**
     * @var \Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     * })
     */
    private $proveedor;



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
     * Set numeroFactura
     *
     * @param string $numeroFactura
     * @return EstadoFactura
     */
    public function setNumeroFactura($numeroFactura)
    {
        $this->numeroFactura = $numeroFactura;

        return $this;
    }

    /**
     * Get numeroFactura
     *
     * @return string 
     */
    public function getNumeroFactura()
    {
        return $this->numeroFactura;
    }

    /**
     * Set cantidadRemisiones
     *
     * @param integer $cantidadRemisiones
     * @return EstadoFactura
     */
    public function setCantidadRemisiones($cantidadRemisiones)
    {
        $this->cantidadRemisiones = $cantidadRemisiones;

        return $this;
    }

    /**
     * Get cantidadRemisiones
     *
     * @return integer 
     */
    public function getCantidadRemisiones()
    {
        return $this->cantidadRemisiones;
    }

    /**
     * Set cantidadPedidos
     *
     * @param integer $cantidadPedidos
     * @return EstadoFactura
     */
    public function setCantidadPedidos($cantidadPedidos)
    {
        $this->cantidadPedidos = $cantidadPedidos;

        return $this;
    }

    /**
     * Get cantidadPedidos
     *
     * @return integer 
     */
    public function getCantidadPedidos()
    {
        return $this->cantidadPedidos;
    }

    /**
     * Set fechaConfirmacion
     *
     * @param \DateTime $fechaConfirmacion
     * @return EstadoFactura
     */
    public function setFechaConfirmacion($fechaConfirmacion)
    {
        $this->fechaConfirmacion = $fechaConfirmacion;

        return $this;
    }

    /**
     * Get fechaConfirmacion
     *
     * @return \DateTime 
     */
    public function getFechaConfirmacion()
    {
        return $this->fechaConfirmacion;
    }

    /**
     * Set estadoProcesamiento
     *
     * @param integer $estadoProcesamiento
     * @return EstadoFactura
     */
    public function setEstadoProcesamiento($estadoProcesamiento)
    {
        $this->estadoProcesamiento = $estadoProcesamiento;

        return $this;
    }

    /**
     * Get estadoProcesamiento
     *
     * @return integer 
     */
    public function getEstadoProcesamiento()
    {
        return $this->estadoProcesamiento;
    }

    /**
     * Set estadoFtp
     *
     * @param integer $estadoFtp
     * @return EstadoFactura
     */
    public function setEstadoFtp($estadoFtp)
    {
        $this->estadoFtp = $estadoFtp;

        return $this;
    }

    /**
     * Get estadoFtp
     *
     * @return integer 
     */
    public function getEstadoFtp()
    {
        return $this->estadoFtp;
    }

    /**
     * Set proveedor
     *
     * @param \FTP\AdministradorBundle\Entity\Proveedor $proveedor
     * @return EstadoFactura
     */
    public function setProveedor(\FTP\AdministradorBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \FTP\AdministradorBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }
}
