<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PedidoDescripcion
 *
 * @ORM\Table(name="pedido_descripcion", indexes={@ORM\Index(name="pdto_codigo_barras", columns={"producto_codigo_barras"}), @ORM\Index(name="asoc_codigo", columns={"drogueria_id"}), @ORM\Index(name="pddo_id", columns={"pedido_id"}), @ORM\Index(name="linea_id", columns={"linea_id"}), @ORM\Index(name="transferencista_id", columns={"transferencista_id"})})
 * @ORM\Entity
 */
class PedidoDescripcion
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
     * @var integer
     *
     * @ORM\Column(name="pdto_id", type="integer", nullable=false)
     */
    private $pdtoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="drogueria_id", type="integer", nullable=false)
     */
    private $drogueriaId;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_pedida", type="integer", nullable=false)
     */
    private $cantidadPedida;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_codigo", type="string", length=20, nullable=false)
     */
    private $productoCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_codigo_barras", type="string", length=20, nullable=false)
     */
    private $productoCodigoBarras;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_descripcion", type="text", nullable=false)
     */
    private $productoDescripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_presentacion", type="string", length=20, nullable=false)
     */
    private $productoPresentacion;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_precio", type="string", length=10, nullable=false)
     */
    private $productoPrecio;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_marcado", type="string", length=10, nullable=false)
     */
    private $productoMarcado;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_real", type="string", length=10, nullable=false)
     */
    private $productoReal;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_iva", type="string", length=10, nullable=false)
     */
    private $productoIva;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_descuento", type="string", length=10, nullable=false)
     */
    private $productoDescuento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="producto__fecha", type="datetime", nullable=false)
     */
    private $productoFecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="producto__estado", type="integer", nullable=false)
     */
    private $productoEstado;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad_final", type="string", length=5, nullable=false)
     */
    private $cantidadFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="producto_foto", type="string", length=30, nullable=false)
     */
    private $productoFoto;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="bonificacion", type="integer", nullable=true)
     */
    private $bonificacion;

    /**
     * @var \Linea
     *
     * @ORM\ManyToOne(targetEntity="Linea")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="linea_id", referencedColumnName="id")
     * })
     */
    private $linea;

    /**
     * @var \Transferencista
     *
     * @ORM\ManyToOne(targetEntity="Transferencista")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="transferencista_id", referencedColumnName="id")
     * })
     */
    private $transferencista;

    /**
     * @var \Pedido
     *
     * @ORM\ManyToOne(targetEntity="Pedido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pedido_id", referencedColumnName="id")
     * })
     */
    private $pedido;



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
     * Set pdtoId
     *
     * @param integer $pdtoId
     * @return PedidoDescripcion
     */
    public function setPdtoId($pdtoId)
    {
        $this->pdtoId = $pdtoId;

        return $this;
    }

    /**
     * Get pdtoId
     *
     * @return integer 
     */
    public function getPdtoId()
    {
        return $this->pdtoId;
    }

    /**
     * Set drogueriaId
     *
     * @param integer $drogueriaId
     * @return PedidoDescripcion
     */
    public function setDrogueriaId($drogueriaId)
    {
        $this->drogueriaId = $drogueriaId;

        return $this;
    }

    /**
     * Get drogueriaId
     *
     * @return integer 
     */
    public function getDrogueriaId()
    {
        return $this->drogueriaId;
    }

    /**
     * Set cantidadPedida
     *
     * @param integer $cantidadPedida
     * @return PedidoDescripcion
     */
    public function setCantidadPedida($cantidadPedida)
    {
        $this->cantidadPedida = $cantidadPedida;

        return $this;
    }

    /**
     * Get cantidadPedida
     *
     * @return integer 
     */
    public function getCantidadPedida()
    {
        return $this->cantidadPedida;
    }

    /**
     * Set productoCodigo
     *
     * @param string $productoCodigo
     * @return PedidoDescripcion
     */
    public function setProductoCodigo($productoCodigo)
    {
        $this->productoCodigo = $productoCodigo;

        return $this;
    }

    /**
     * Get productoCodigo
     *
     * @return string 
     */
    public function getProductoCodigo()
    {
        return $this->productoCodigo;
    }

    /**
     * Set productoCodigoBarras
     *
     * @param string $productoCodigoBarras
     * @return PedidoDescripcion
     */
    public function setProductoCodigoBarras($productoCodigoBarras)
    {
        $this->productoCodigoBarras = $productoCodigoBarras;

        return $this;
    }

    /**
     * Get productoCodigoBarras
     *
     * @return string 
     */
    public function getProductoCodigoBarras()
    {
        return $this->productoCodigoBarras;
    }

    /**
     * Set productoDescripcion
     *
     * @param string $productoDescripcion
     * @return PedidoDescripcion
     */
    public function setProductoDescripcion($productoDescripcion)
    {
        $this->productoDescripcion = $productoDescripcion;

        return $this;
    }

    /**
     * Get productoDescripcion
     *
     * @return string 
     */
    public function getProductoDescripcion()
    {
        return $this->productoDescripcion;
    }

    /**
     * Set productoPresentacion
     *
     * @param string $productoPresentacion
     * @return PedidoDescripcion
     */
    public function setProductoPresentacion($productoPresentacion)
    {
        $this->productoPresentacion = $productoPresentacion;

        return $this;
    }

    /**
     * Get productoPresentacion
     *
     * @return string 
     */
    public function getProductoPresentacion()
    {
        return $this->productoPresentacion;
    }

    /**
     * Set productoPrecio
     *
     * @param string $productoPrecio
     * @return PedidoDescripcion
     */
    public function setProductoPrecio($productoPrecio)
    {
        $this->productoPrecio = $productoPrecio;

        return $this;
    }

    /**
     * Get productoPrecio
     *
     * @return string 
     */
    public function getProductoPrecio()
    {
        return $this->productoPrecio;
    }

    /**
     * Set productoMarcado
     *
     * @param string $productoMarcado
     * @return PedidoDescripcion
     */
    public function setProductoMarcado($productoMarcado)
    {
        $this->productoMarcado = $productoMarcado;

        return $this;
    }

    /**
     * Get productoMarcado
     *
     * @return string 
     */
    public function getProductoMarcado()
    {
        return $this->productoMarcado;
    }

    /**
     * Set productoReal
     *
     * @param string $productoReal
     * @return PedidoDescripcion
     */
    public function setProductoReal($productoReal)
    {
        $this->productoReal = $productoReal;

        return $this;
    }

    /**
     * Get productoReal
     *
     * @return string 
     */
    public function getProductoReal()
    {
        return $this->productoReal;
    }

    /**
     * Set productoIva
     *
     * @param string $productoIva
     * @return PedidoDescripcion
     */
    public function setProductoIva($productoIva)
    {
        $this->productoIva = $productoIva;

        return $this;
    }

    /**
     * Get productoIva
     *
     * @return string 
     */
    public function getProductoIva()
    {
        return $this->productoIva;
    }

    /**
     * Set productoDescuento
     *
     * @param string $productoDescuento
     * @return PedidoDescripcion
     */
    public function setProductoDescuento($productoDescuento)
    {
        $this->productoDescuento = $productoDescuento;

        return $this;
    }

    /**
     * Get productoDescuento
     *
     * @return string 
     */
    public function getProductoDescuento()
    {
        return $this->productoDescuento;
    }

    /**
     * Set productoFecha
     *
     * @param \DateTime $productoFecha
     * @return PedidoDescripcion
     */
    public function setProductoFecha($productoFecha)
    {
        $this->productoFecha = $productoFecha;

        return $this;
    }

    /**
     * Get productoFecha
     *
     * @return \DateTime 
     */
    public function getProductoFecha()
    {
        return $this->productoFecha;
    }

    /**
     * Set productoEstado
     *
     * @param integer $productoEstado
     * @return PedidoDescripcion
     */
    public function setProductoEstado($productoEstado)
    {
        $this->productoEstado = $productoEstado;

        return $this;
    }

    /**
     * Get productoEstado
     *
     * @return integer 
     */
    public function getProductoEstado()
    {
        return $this->productoEstado;
    }

    /**
     * Set cantidadFinal
     *
     * @param string $cantidadFinal
     * @return PedidoDescripcion
     */
    public function setCantidadFinal($cantidadFinal)
    {
        $this->cantidadFinal = $cantidadFinal;

        return $this;
    }

    /**
     * Get cantidadFinal
     *
     * @return string 
     */
    public function getCantidadFinal()
    {
        return $this->cantidadFinal;
    }

    /**
     * Set productoFoto
     *
     * @param string $productoFoto
     * @return PedidoDescripcion
     */
    public function setProductoFoto($productoFoto)
    {
        $this->productoFoto = $productoFoto;

        return $this;
    }

    /**
     * Get productoFoto
     *
     * @return string 
     */
    public function getProductoFoto()
    {
        return $this->productoFoto;
    }
    
    /**
     * Set bonificacion
     *
     * @param integer $bonificacion
     * @return PedidoDescripcion
     */
    public function setBonificacion($bonificacion)
    {
        $this->bonificacion = $bonificacion;

        return $this;
    }

    /**
     * Get bonificacion
     *
     * @return integer 
     */
    public function getBonificacion()
    {
        return $this->bonificacion;
    }

    /**
     * Set linea
     *
     * @param \FTP\AdministradorBundle\Entity\Linea $linea
     * @return PedidoDescripcion
     */
    public function setLinea(\FTP\AdministradorBundle\Entity\Linea $linea = null)
    {
        $this->linea = $linea;

        return $this;
    }

    /**
     * Get linea
     *
     * @return \FTP\AdministradorBundle\Entity\Linea 
     */
    public function getLinea()
    {
        return $this->linea;
    }

    /**
     * Set transferencista
     *
     * @param \FTP\AdministradorBundle\Entity\Transferencista $transferencista
     * @return PedidoDescripcion
     */
    public function setTransferencista(\FTP\AdministradorBundle\Entity\Transferencista $transferencista = null)
    {
        $this->transferencista = $transferencista;

        return $this;
    }

    /**
     * Get transferencista
     *
     * @return \FTP\AdministradorBundle\Entity\Transferencista 
     */
    public function getTransferencista()
    {
        return $this->transferencista;
    }

    /**
     * Set pedido
     *
     * @param \FTP\AdministradorBundle\Entity\Pedido $pedido
     * @return PedidoDescripcion
     */
    public function setPedido(\FTP\AdministradorBundle\Entity\Pedido $pedido = null)
    {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * Get pedido
     *
     * @return \FTP\AdministradorBundle\Entity\Pedido 
     */
    public function getPedido()
    {
        return $this->pedido;
    }
}
