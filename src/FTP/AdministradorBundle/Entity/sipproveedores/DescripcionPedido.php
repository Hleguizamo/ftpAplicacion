<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * DescripcionPedido
 *
 * @ORM\Table(name="descripcion_pedido", indexes={@ORM\Index(name="id_pedido", columns={"id_pedido"}), @ORM\Index(name="estado_pedido", columns={"estado_pedido"}), @ORM\Index(name="centro", columns={"centro"}), @ORM\Index(name="codigo_asociado", columns={"codigo_asociado"}), @ORM\Index(name="id_transferencista", columns={"id_transferencista"}), @ORM\Index(name="material", columns={"material"})})
 * @ORM\Entity
 */
class DescripcionPedido
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
     * @ORM\Column(name="centro", type="string", length=100, nullable=false)
     */
    private $centro;

    /**
     * @var string
     *
     * @ORM\Column(name="clasificacion", type="string", length=100, nullable=false)
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
     * @ORM\Column(name="lote", type="string", length=100, nullable=false)
     */
    private $lote;

    /**
     * @var string
     *
     * @ORM\Column(name="empaque", type="string", length=20, nullable=false)
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
     * @ORM\Column(name="impuesto", type="string", length=100, nullable=false)
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
     * @ORM\Column(name="tiempo", type="date", nullable=false)
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
     * @ORM\Column(name="codigo_barras", type="string", length=20, nullable=false)
     */
    private $codigoBarras;

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo_asociado", type="integer", nullable=false)
     */
    private $codigoAsociado;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_pedida", type="integer", nullable=false)
     */
    private $cantidadPedida;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_productos", type="integer", nullable=false)
     */
    private $cantidadProductos = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="id_transferencista", type="integer", nullable=false)
     */
    private $idTransferencista;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado_pedido", type="integer", nullable=false)
     */
    private $estadoPedido;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_pedido", type="string", length=4, nullable=false)
     */
    private $tipoPedido;

    /**
     * @var string
     *
     * @ORM\Column(name="expedicion", type="string", length=2, nullable=false)
     */
    private $expedicion;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo_solicitud", type="integer", nullable=false)
     */
    private $tipoSolicitud;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tipo_ingreso", type="boolean", nullable=false)
     */
    private $tipoIngreso;

    /**
     * @var boolean
     *
     * @ORM\Column(name="descuentos", type="boolean", nullable=false)
     */
    private $descuentos;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_pedido", type="integer", nullable=false)
     */
    private $idPedido;

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
     * @var string
     *
     * @ORM\Column(name="division", type="string", length=2, nullable=true)
     */
    private $division;

    /**
     * @var float
     *
     * @ORM\Column(name="iva", type="float", precision=10, scale=0, nullable=false)
     */
    private $iva;
	
	/**
     * Set centro
     *
     * @param string $centro
     * @return DescripcionPedido
     */
    public function setCentro($centro)
    {
        $this->centro = $centro;

        return $this;
    }

    /**
     * Get centro
     *
     * @return string 
     */
    public function getCentro()
    {
        return $this->centro;
    }
	
	/**
     * Set clasificacion
     *
     * @param string $clasificacion
     * @return DescripcionPedido
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
     * @return DescripcionPedido
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
     * @return DescripcionPedido
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
     * @return DescripcionPedido
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
     * @return DescripcionPedido
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
     * @return DescripcionPedido
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

    function getId() {
        return $this->id;
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

    function getCodigoBarras() {
        return $this->codigoBarras;
    }

    function getCodigoAsociado() {
        return $this->codigoAsociado;
    }

    function getCantidadPedida() {
        return $this->cantidadPedida;
    }

    function getCantidadProductos() {
        return $this->cantidadProductos;
    }

    function getIdTransferencista() {
        return $this->idTransferencista;
    }

    function getEstadoPedido() {
        return $this->estadoPedido;
    }

    function getTipoPedido() {
        return $this->tipoPedido;
    }

    function getExpedicion() {
        return $this->expedicion;
    }

    function getTipoSolicitud() {
        return $this->tipoSolicitud;
    }

    function getTipoIngreso() {
        return $this->tipoIngreso;
    }

    function getDescuentos() {
        return $this->descuentos;
    }

    function getIdPedido() {
        return $this->idPedido;
    }

    function getDisponibilidad() {
        return $this->disponibilidad;
    }

    function getObsequio() {
        return $this->obsequio;
    }

    function getDivision() {
        return $this->division;
    }

    function getIva() {
        return $this->iva;
    }

    function setId($id) {
        $this->id = $id;
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

    function setCodigoBarras($codigoBarras) {
        $this->codigoBarras = $codigoBarras;
    }

    function setCodigoAsociado($codigoAsociado) {
        $this->codigoAsociado = $codigoAsociado;
    }

    function setCantidadPedida($cantidadPedida) {
        $this->cantidadPedida = $cantidadPedida;
    }

    function setCantidadProductos($cantidadProductos) {
        $this->cantidadProductos = $cantidadProductos;
    }

    function setIdTransferencista($idTransferencista) {
        $this->idTransferencista = $idTransferencista;
    }

    function setEstadoPedido($estadoPedido) {
        $this->estadoPedido = $estadoPedido;
    }

    function setTipoPedido($tipoPedido) {
        $this->tipoPedido = $tipoPedido;
    }

    function setExpedicion($expedicion) {
        $this->expedicion = $expedicion;
    }

    function setTipoSolicitud($tipoSolicitud) {
        $this->tipoSolicitud = $tipoSolicitud;
    }

    function setTipoIngreso($tipoIngreso) {
        $this->tipoIngreso = $tipoIngreso;
    }

    function setDescuentos($descuentos) {
        $this->descuentos = $descuentos;
    }

    function setIdPedido($idPedido) {
        $this->idPedido = $idPedido;
    }

    function setDisponibilidad($disponibilidad) {
        $this->disponibilidad = $disponibilidad;
    }

    function setObsequio($obsequio) {
        $this->obsequio = $obsequio;
    }

    function setDivision($division) {
        $this->division = $division;
    }

    function setIva($iva) {
        $this->iva = $iva;
    }



}
