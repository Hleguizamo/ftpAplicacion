<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * InventarioProducto
 *
 * @ORM\Table(name="inventario_producto", indexes={@ORM\Index(name="pedidoDescripcion_codigo_barras", columns={"codigo_barras"}), @ORM\Index(name="pedidoDescripcion_codigo", columns={"codigo"}), @ORM\Index(name="pedidoDescripcion_proveedor_id", columns={"proveedor_id"}), @ORM\Index(name="pedidoDescripcion_linea_id", columns={"linea_id"})})
 * @ORM\Entity
 */
class InventarioProducto
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
     * @ORM\Column(name="codigo", type="string", length=20, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_barras", type="string", length=13, nullable=false)
     */
    private $codigoBarras;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="presentacion", type="string", length=20, nullable=false)
     */
    private $presentacion;

    /**
     * @var float
     *
     * @ORM\Column(name="precio", type="float", precision=18, scale=2, nullable=false)
     */
    private $precio;

    /**
     * @var integer
     *
     * @ORM\Column(name="precio_real", type="integer", nullable=false)
     */
    private $precioReal;

    /**
     * @var integer
     *
     * @ORM\Column(name="iva", type="integer", nullable=false)
     */
    private $iva;

    /**
     * @var string
     *
     * @ORM\Column(name="foto", type="string", length=100, nullable=false)
     */
    private $foto;

    /**
     * @var integer
     *
     * @ORM\Column(name="descuento", type="integer", nullable=false)
     */
    private $descuento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creado", type="datetime", nullable=false)
     */
    private $fechaCreado = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_actualizado", type="datetime", nullable=false)
     */
    private $fechaActualizado;

    /**
     * @var string
     *
     * @ORM\Column(name="foto_temporal", type="string", length=50, nullable=false)
     */
    private $fotoTemporal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inicio_descuento", type="date", nullable=false)
     */
    private $inicioDescuento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin_descuento", type="date", nullable=false)
     */
    private $finDescuento;

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
     * @var \Linea
     *
     * @ORM\ManyToOne(targetEntity="Linea")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="linea_id", referencedColumnName="id")
     * })
     */
    private $linea;



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
     * Set codigo
     *
     * @param string $codigo
     * @return InventarioProducto
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set codigoBarras
     *
     * @param string $codigoBarras
     * @return InventarioProducto
     */
    public function setCodigoBarras($codigoBarras)
    {
        $this->codigoBarras = $codigoBarras;

        return $this;
    }

    /**
     * Get codigoBarras
     *
     * @return string 
     */
    public function getCodigoBarras()
    {
        return $this->codigoBarras;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return InventarioProducto
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set presentacion
     *
     * @param string $presentacion
     * @return InventarioProducto
     */
    public function setPresentacion($presentacion)
    {
        $this->presentacion = $presentacion;

        return $this;
    }

    /**
     * Get presentacion
     *
     * @return string 
     */
    public function getPresentacion()
    {
        return $this->presentacion;
    }

    /**
     * Set precio
     *
     * @param float $precio
     * @return InventarioProducto
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get precio
     *
     * @return float 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set precioReal
     *
     * @param integer $precioReal
     * @return InventarioProducto
     */
    public function setPrecioReal($precioReal)
    {
        $this->precioReal = $precioReal;

        return $this;
    }

    /**
     * Get precioReal
     *
     * @return integer 
     */
    public function getPrecioReal()
    {
        return $this->precioReal;
    }

    /**
     * Set iva
     *
     * @param integer $iva
     * @return InventarioProducto
     */
    public function setIva($iva)
    {
        $this->iva = $iva;

        return $this;
    }

    /**
     * Get iva
     *
     * @return integer 
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set foto
     *
     * @param string $foto
     * @return InventarioProducto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto
     *
     * @return string 
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set descuento
     *
     * @param integer $descuento
     * @return InventarioProducto
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;

        return $this;
    }

    /**
     * Get descuento
     *
     * @return integer 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return InventarioProducto
     */
    public function setFechaCreado($fechaCreado)
    {
        $this->fechaCreado = $fechaCreado;

        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime 
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * Set fechaActualizado
     *
     * @param \DateTime $fechaActualizado
     * @return InventarioProducto
     */
    public function setFechaActualizado($fechaActualizado)
    {
        $this->fechaActualizado = $fechaActualizado;

        return $this;
    }

    /**
     * Get fechaActualizado
     *
     * @return \DateTime 
     */
    public function getFechaActualizado()
    {
        return $this->fechaActualizado;
    }

    /**
     * Set fotoTemporal
     *
     * @param string $fotoTemporal
     * @return InventarioProducto
     */
    public function setFotoTemporal($fotoTemporal)
    {
        $this->fotoTemporal = $fotoTemporal;

        return $this;
    }

    /**
     * Get fotoTemporal
     *
     * @return string 
     */
    public function getFotoTemporal()
    {
        return $this->fotoTemporal;
    }

    /**
     * Set inicioDescuento
     *
     * @param \DateTime $inicioDescuento
     * @return InventarioProducto
     */
    public function setInicioDescuento($inicioDescuento)
    {
        $this->inicioDescuento = $inicioDescuento;

        return $this;
    }

    /**
     * Get inicioDescuento
     *
     * @return \DateTime 
     */
    public function getInicioDescuento()
    {
        return $this->inicioDescuento;
    }

    /**
     * Set finDescuento
     *
     * @param \DateTime $finDescuento
     * @return InventarioProducto
     */
    public function setFinDescuento($finDescuento)
    {
        $this->finDescuento = $finDescuento;

        return $this;
    }

    /**
     * Get finDescuento
     *
     * @return \DateTime 
     */
    public function getFinDescuento()
    {
        return $this->finDescuento;
    }

    /**
     * Set proveedor
     *
     * @param \FTP\AdministradorBundle\Entity\Proveedor $proveedor
     * @return InventarioProducto
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

    /**
     * Set linea
     *
     * @param \FTP\AdministradorBundle\Entity\Linea $linea
     * @return InventarioProducto
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
}
