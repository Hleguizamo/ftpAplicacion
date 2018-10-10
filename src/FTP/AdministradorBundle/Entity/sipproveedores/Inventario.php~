<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventario
 *
 * @ORM\Table(name="inventario", indexes={@ORM\Index(name="material", columns={"material"}), @ORM\Index(name="lote", columns={"lote"}), @ORM\Index(name="centro", columns={"centro"})})
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
     * @var string
     *
     * @ORM\Column(name="division", type="string", length=2, nullable=true)
     */
    private $division;

    /**
     * @var integer
     *
     * @ORM\Column(name="plan", type="integer", nullable=false)
     */
    private $plan = '0';

	
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
     * Set centro
     *
     * @param string $centro
     * @return Inventario
     */
    public function setCentro($centro)
    {
        $this->centro = $centro;

        return $this;
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

    
    function getId() {
        return $this->id;
    }

    function getProveedorId() {
        return $this->proveedorId;
    }

    function getProveedor() {
        return $this->proveedor;
    }

    function getMaterial() {
        return $this->material;
    }

    function getDenominacion() {
        return $this->denominacion;
    }

    function getLote() {
        return $this->lote;
    }

    function getEmpaque() {
        return $this->empaque;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getPrecioReal() {
        return $this->precioReal;
    }

    function getPrecioCorriente() {
        return $this->precioCorriente;
    }

    function getImpuesto() {
        return $this->impuesto;
    }

    function getPrecioMarcado() {
        return $this->precioMarcado;
    }

    function getBonificacion() {
        return $this->bonificacion;
    }

    function getTiempo() {
        return $this->tiempo;
    }

    function getFechaIngreso() {
        return $this->fechaIngreso;
    }

    function getPlazo() {
        return $this->plazo;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function getGrupo() {
        return $this->grupo;
    }

    function getSubGrupo() {
        return $this->subGrupo;
    }

    function getNCategoria() {
        return $this->nCategoria;
    }

    function getNGrupo() {
        return $this->nGrupo;
    }

    function getNSubGrupo() {
        return $this->nSubGrupo;
    }

    function getCodigoBarras() {
        return $this->codigoBarras;
    }

    function getDisponibilidad() {
        return $this->disponibilidad;
    }

    function getObsequio() {
        return $this->obsequio;
    }

    function getMaximoXPedido() {
        return $this->maximoXPedido;
    }

    function getDivision() {
        return $this->division;
    }

    function getPlan() {
        return $this->plan;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setProveedorId($proveedorId) {
        $this->proveedorId = $proveedorId;
    }

    function setProveedor($proveedor) {
        $this->proveedor = $proveedor;
    }

    function setMaterial($material) {
        $this->material = $material;
    }

    function setDenominacion($denominacion) {
        $this->denominacion = $denominacion;
    }

    function setLote($lote) {
        $this->lote = $lote;
    }

    function setEmpaque($empaque) {
        $this->empaque = $empaque;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setPrecioReal($precioReal) {
        $this->precioReal = $precioReal;
    }

    function setPrecioCorriente($precioCorriente) {
        $this->precioCorriente = $precioCorriente;
    }

    function setImpuesto($impuesto) {
        $this->impuesto = $impuesto;
    }

    function setPrecioMarcado($precioMarcado) {
        $this->precioMarcado = $precioMarcado;
    }

    function setBonificacion($bonificacion) {
        $this->bonificacion = $bonificacion;
    }

    function setTiempo(\DateTime $tiempo) {
        $this->tiempo = $tiempo;
    }

    function setFechaIngreso(\DateTime $fechaIngreso) {
        $this->fechaIngreso = $fechaIngreso;
    }

    function setPlazo($plazo) {
        $this->plazo = $plazo;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    function setSubGrupo($subGrupo) {
        $this->subGrupo = $subGrupo;
    }

    function setNCategoria($nCategoria) {
        $this->nCategoria = $nCategoria;
    }

    function setNGrupo($nGrupo) {
        $this->nGrupo = $nGrupo;
    }

    function setNSubGrupo($nSubGrupo) {
        $this->nSubGrupo = $nSubGrupo;
    }

    function setCodigoBarras($codigoBarras) {
        $this->codigoBarras = $codigoBarras;
    }

    function setDisponibilidad($disponibilidad) {
        $this->disponibilidad = $disponibilidad;
    }

    function setObsequio($obsequio) {
        $this->obsequio = $obsequio;
    }

    function setMaximoXPedido($maximoXPedido) {
        $this->maximoXPedido = $maximoXPedido;
    }

    function setDivision($division) {
        $this->division = $division;
    }

    function setPlan($plan) {
        $this->plan = $plan;
    }


    
}
