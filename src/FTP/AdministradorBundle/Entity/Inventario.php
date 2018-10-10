<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventario
 *
 * @ORM\Table(name="inventario", indexes={@ORM\Index(name="centro", columns={"centro"}), @ORM\Index(name="categoria", columns={"categoria"}), @ORM\Index(name="grupo", columns={"grupo"}), @ORM\Index(name="sub_grupo", columns={"sub_grupo"}), @ORM\Index(name="n_categoria", columns={"n_categoria"}), @ORM\Index(name="n_grupo", columns={"n_grupo"}), @ORM\Index(name="codigo_barras", columns={"codigo_barras"}), @ORM\Index(name="material", columns={"material"}), @ORM\Index(name="clasificacion", columns={"clasificacion"})})
 * @ORM\Entity
 */
class Inventario
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
     * @ORM\Column(name="centro", type="integer", nullable=true)
     */
    private $centro;

    /**
     * @var string
     *
     * @ORM\Column(name="clasificacion", type="string", length=4, nullable=false)
     */
    private $clasificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor_id", type="string", length=3, nullable=false)
     */
    private $proveedorId;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor", type="string", length=100, nullable=false)
     */
    private $proveedor;

    /**
     * @var integer
     *
     * @ORM\Column(name="material", type="integer", nullable=true)
     */
    private $material;

    /**
     * @var string
     *
     * @ORM\Column(name="denominacion", type="string", length=100, nullable=false)
     */
    private $denominacion;

    /**
     * @var string
     *
     * @ORM\Column(name="lote", type="string", length=50, nullable=false)
     */
    private $lote;

    /**
     * @var string
     *
     * @ORM\Column(name="empaque", type="string", length=30, nullable=false)
     */
    private $empaque;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="precio_real", type="integer", nullable=true)
     */
    private $precioReal;

    /**
     * @var integer
     *
     * @ORM\Column(name="precio_corriente", type="integer", nullable=true)
     */
    private $precioCorriente;

    /**
     * @var string
     *
     * @ORM\Column(name="impuesto", type="string", length=100, nullable=true)
     */
    private $impuesto;

    /**
     * @var integer
     *
     * @ORM\Column(name="precio_marcado", type="integer", nullable=true)
     */
    private $precioMarcado;

    /**
     * @var float
     *
     * @ORM\Column(name="bonificacion", type="float", precision=18, scale=2, nullable=true)
     */
    private $bonificacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tiempo", type="date", nullable=true)
     */
    private $tiempo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_ingreso", type="date", nullable=false)
     */
    private $fechaIngreso = '0000-00-00';

    /**
     * @var string
     *
     * @ORM\Column(name="plazo", type="string", length=5, nullable=false)
     */
    private $plazo;

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=4, nullable=false)
     */
    private $categoria;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=4, nullable=false)
     */
    private $grupo;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_grupo", type="string", length=4, nullable=false)
     */
    private $subGrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="n_categoria", type="string", length=50, nullable=false)
     */
    private $nCategoria;

    /**
     * @var string
     *
     * @ORM\Column(name="n_grupo", type="string", length=50, nullable=false)
     */
    private $nGrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="n_sub_grupo", type="string", length=50, nullable=false)
     */
    private $nSubGrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_barras", type="string", length=20, nullable=false)
     */
    private $codigoBarras;

    /**
     * @var string
     *
     * @ORM\Column(name="disponibilidad", type="string", length=10, nullable=false)
     */
    private $disponibilidad;

    /**
     * @var string
     *
     * @ORM\Column(name="obsequio", type="string", length=10, nullable=false)
     */
    private $obsequio;

    /**
     * @var integer
     *
     * @ORM\Column(name="maximo_x_pedido", type="smallint", nullable=true)
     */
    private $maximoXPedido = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="lotes", type="integer", nullable=false)
     */
    private $lotes;



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
     * Set centro
     *
     * @param integer $centro
     * @return Inventario
     */
    public function setCentro($centro)
    {
        $this->centro = $centro;
    
        return $this;
    }

    /**
     * Get centro
     *
     * @return integer 
     */
    public function getCentro()
    {
        return $this->centro;
    }

    /**
     * Set clasificacion
     *
     * @param string $clasificacion
     * @return Inventario
     */
    public function setClasificacion($clasificacion)
    {
        $this->clasificacion = $clasificacion;
    
        return $this;
    }

    /**
     * Get clasificacion
     *
     * @return string 
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * Set proveedorId
     *
     * @param string $proveedorId
     * @return Inventario
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;
    
        return $this;
    }

    /**
     * Get proveedorId
     *
     * @return string 
     */
    public function getProveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Set proveedor
     *
     * @param string $proveedor
     * @return Inventario
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
     * Set material
     *
     * @param integer $material
     * @return Inventario
     */
    public function setMaterial($material)
    {
        $this->material = $material;
    
        return $this;
    }

    /**
     * Get material
     *
     * @return integer 
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Set denominacion
     *
     * @param string $denominacion
     * @return Inventario
     */
    public function setDenominacion($denominacion)
    {
        $this->denominacion = $denominacion;
    
        return $this;
    }

    /**
     * Get denominacion
     *
     * @return string 
     */
    public function getDenominacion()
    {
        return $this->denominacion;
    }

    /**
     * Set lote
     *
     * @param string $lote
     * @return Inventario
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
    
        return $this;
    }

    /**
     * Get lote
     *
     * @return string 
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set empaque
     *
     * @param string $empaque
     * @return Inventario
     */
    public function setEmpaque($empaque)
    {
        $this->empaque = $empaque;
    
        return $this;
    }

    /**
     * Get empaque
     *
     * @return string 
     */
    public function getEmpaque()
    {
        return $this->empaque;
    }

    /**
     * Set cantidad
     *
     * @param integer $cantidad
     * @return Inventario
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
     * Set precioReal
     *
     * @param integer $precioReal
     * @return Inventario
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
     * Set precioCorriente
     *
     * @param integer $precioCorriente
     * @return Inventario
     */
    public function setPrecioCorriente($precioCorriente)
    {
        $this->precioCorriente = $precioCorriente;
    
        return $this;
    }

    /**
     * Get precioCorriente
     *
     * @return integer 
     */
    public function getPrecioCorriente()
    {
        return $this->precioCorriente;
    }

    /**
     * Set impuesto
     *
     * @param string $impuesto
     * @return Inventario
     */
    public function setImpuesto($impuesto)
    {
        $this->impuesto = $impuesto;
    
        return $this;
    }

    /**
     * Get impuesto
     *
     * @return string 
     */
    public function getImpuesto()
    {
        return $this->impuesto;
    }

    /**
     * Set precioMarcado
     *
     * @param integer $precioMarcado
     * @return Inventario
     */
    public function setPrecioMarcado($precioMarcado)
    {
        $this->precioMarcado = $precioMarcado;
    
        return $this;
    }

    /**
     * Get precioMarcado
     *
     * @return integer 
     */
    public function getPrecioMarcado()
    {
        return $this->precioMarcado;
    }

    /**
     * Set bonificacion
     *
     * @param float $bonificacion
     * @return Inventario
     */
    public function setBonificacion($bonificacion)
    {
        $this->bonificacion = $bonificacion;
    
        return $this;
    }

    /**
     * Get bonificacion
     *
     * @return float 
     */
    public function getBonificacion()
    {
        return $this->bonificacion;
    }

    /**
     * Set tiempo
     *
     * @param \DateTime $tiempo
     * @return Inventario
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;
    
        return $this;
    }

    /**
     * Get tiempo
     *
     * @return \DateTime 
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     * @return Inventario
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;
    
        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime 
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set plazo
     *
     * @param string $plazo
     * @return Inventario
     */
    public function setPlazo($plazo)
    {
        $this->plazo = $plazo;
    
        return $this;
    }

    /**
     * Get plazo
     *
     * @return string 
     */
    public function getPlazo()
    {
        return $this->plazo;
    }

    /**
     * Set categoria
     *
     * @param string $categoria
     * @return Inventario
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return string 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set grupo
     *
     * @param string $grupo
     * @return Inventario
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    
        return $this;
    }

    /**
     * Get grupo
     *
     * @return string 
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set subGrupo
     *
     * @param string $subGrupo
     * @return Inventario
     */
    public function setSubGrupo($subGrupo)
    {
        $this->subGrupo = $subGrupo;
    
        return $this;
    }

    /**
     * Get subGrupo
     *
     * @return string 
     */
    public function getSubGrupo()
    {
        return $this->subGrupo;
    }

    /**
     * Set nCategoria
     *
     * @param string $nCategoria
     * @return Inventario
     */
    public function setNCategoria($nCategoria)
    {
        $this->nCategoria = $nCategoria;
    
        return $this;
    }

    /**
     * Get nCategoria
     *
     * @return string 
     */
    public function getNCategoria()
    {
        return $this->nCategoria;
    }

    /**
     * Set nGrupo
     *
     * @param string $nGrupo
     * @return Inventario
     */
    public function setNGrupo($nGrupo)
    {
        $this->nGrupo = $nGrupo;
    
        return $this;
    }

    /**
     * Get nGrupo
     *
     * @return string 
     */
    public function getNGrupo()
    {
        return $this->nGrupo;
    }

    /**
     * Set nSubGrupo
     *
     * @param string $nSubGrupo
     * @return Inventario
     */
    public function setNSubGrupo($nSubGrupo)
    {
        $this->nSubGrupo = $nSubGrupo;
    
        return $this;
    }

    /**
     * Get nSubGrupo
     *
     * @return string 
     */
    public function getNSubGrupo()
    {
        return $this->nSubGrupo;
    }

    /**
     * Set codigoBarras
     *
     * @param string $codigoBarras
     * @return Inventario
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
     * Set disponibilidad
     *
     * @param string $disponibilidad
     * @return Inventario
     */
    public function setDisponibilidad($disponibilidad)
    {
        $this->disponibilidad = $disponibilidad;
    
        return $this;
    }

    /**
     * Get disponibilidad
     *
     * @return string 
     */
    public function getDisponibilidad()
    {
        return $this->disponibilidad;
    }

    /**
     * Set obsequio
     *
     * @param string $obsequio
     * @return Inventario
     */
    public function setObsequio($obsequio)
    {
        $this->obsequio = $obsequio;
    
        return $this;
    }

    /**
     * Get obsequio
     *
     * @return string 
     */
    public function getObsequio()
    {
        return $this->obsequio;
    }

    /**
     * Set maximoXPedido
     *
     * @param integer $maximoXPedido
     * @return Inventario
     */
    public function setMaximoXPedido($maximoXPedido)
    {
        $this->maximoXPedido = $maximoXPedido;
    
        return $this;
    }

    /**
     * Get maximoXPedido
     *
     * @return integer 
     */
    public function getMaximoXPedido()
    {
        return $this->maximoXPedido;
    }

    /**
     * Set lotes
     *
     * @param integer $lotes
     * @return Inventario
     */
    public function setLotes($lotes)
    {
        $this->lotes = $lotes;
    
        return $this;
    }

    /**
     * Get lotes
     *
     * @return integer 
     */
    public function getLotes()
    {
        return $this->lotes;
    }
}
