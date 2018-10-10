<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pedidos
 *
 * @ORM\Table(name="pedidos", indexes={@ORM\Index(name="evento", columns={"evento"}), @ORM\Index(name="id_transferencista", columns={"id_transferencista"}), @ORM\Index(name="codigo_asociado", columns={"codigo_asociado"})})
 * @ORM\Entity
 */
class Pedidos
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
     * @ORM\Column(name="codigo_pedido", type="string", length=50, nullable=false)
     */
    private $codigoPedido;

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo_asociado", type="integer", nullable=true)
     */
    private $codigoAsociado;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad_productos", type="integer", nullable=true)
     */
    private $cantidadProductos;

    /**
     * @var integer
     *
     * @ORM\Column(name="precio_pedido", type="integer", nullable=true)
     */
    private $precioPedido;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_confirmado", type="datetime", nullable=true)
     */
    private $fechaConfirmado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=10, nullable=true)
     */
    private $tipo;

    /**
     * @var integer
     *
     * @ORM\Column(name="evento", type="bigint", nullable=true)
     */
    private $evento = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="cupo", type="integer", nullable=true)
     */
    private $cupo;

    /**
     * @var integer
     *
     * @ORM\Column(name="prueba", type="smallint", nullable=true)
     */
    private $prueba = '0';

    /**
     * @var \integer
     *
     * @ORM\Column(name="id_transferencista", type="integer", nullable=false)
     */
    private $idTransferencista;

    /**
     * @var integer
     *
     * @ORM\Column(name="pedidos_sip", type="integer", nullable=true)
     */
    private $pedidosSip;

        /**
     * @var string
     *
     * @ORM\Column(name="divisiones_transferencista", type="string", length=30, nullable=false)
     */
    private $divisionesTransferencista;

    /*  proveedor_id proveedor_razon_social  evento_titulo  tansferencista_nombre */
    /**
     * @var integer
     *
     * @ORM\Column(name="proveedor_id", type="bigint", nullable=true)
     */
    private $proveedorId;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor_razon_social", type="string", length=150, nullable=true)
     */
    private $proveedorRazonSocial;

    /**
     * @var string
     *
     * @ORM\Column(name="evento_titulo", type="string", length=50, nullable=true)
     */
    private $eventoTitulo;

    /**
     * @var string
     *
     * @ORM\Column(name="tansferencista_nombre", type="string", length=150, nullable=true)
     */
    private $tansferencistaNombre;

    /**
     * Set divisionesTransferencista
     *
     * @param string $divisionesTransferencista
     * @return Pedido
     */
    public function setdivisionesTransferencista($divisionesTransferencista)
    {
        $this->divisionesTransferencista = $divisionesTransferencista;

        return $this;
    }
     

    /**
     * Get divisionesTransferencista
     *
     * @return string 
     */
    public function getdivisionesTransferencista()
    {
        return $this->divisionesTransferencista;
    }

    
    function getId() {
        return $this->id;
    }

    function getCodigoPedido() {
        return $this->codigoPedido;
    }

    function getCodigoAsociado() {
        return $this->codigoAsociado;
    }

    function getCantidadProductos() {
        return $this->cantidadProductos;
    }

    function getPrecioPedido() {
        return $this->precioPedido;
    }

    function getFechaConfirmado() {
        return $this->fechaConfirmado;
    }

    function getEstado() {
        return $this->estado;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getEvento() {
        return $this->evento;
    }

    function getCupo() {
        return $this->cupo;
    }

    function getPrueba() {
        return $this->prueba;
    }

    function getIdTransferencista() {
        return $this->idTransferencista;
    }

    /**
     * Get pedidosSip
     *
     * @return integer 
     */
    public function getPedidosSip()
    {
        return $this->pedidosSip;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCodigoPedido($codigoPedido) {
        $this->codigoPedido = $codigoPedido;
    }

    function setCodigoAsociado($codigoAsociado) {
        $this->codigoAsociado = $codigoAsociado;
    }

    function setCantidadProductos($cantidadProductos) {
        $this->cantidadProductos = $cantidadProductos;
    }

    function setPrecioPedido($precioPedido) {
        $this->precioPedido = $precioPedido;
    }

    function setFechaConfirmado(\DateTime $fechaConfirmado) {
        $this->fechaConfirmado = $fechaConfirmado;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setEvento($evento) {
        $this->evento = $evento;
    }

    function setCupo($cupo) {
        $this->cupo = $cupo;
    }

    function setPrueba($prueba) {
        $this->prueba = $prueba;
    }

    function setIdTransferencista($idTransferencista) {
        $this->idTransferencista = $idTransferencista;
    }

    function setPedidosSip($pedidosSip)
    {
        $this->pedidosSip = $pedidosSip;
    }

    /**
     * Set proveedorId
     *
     * @param integer $proveedorId
     * @return Pedidos
     */
    public function setProveedorId($proveedorId)
    {
        $this->proveedorId = $proveedorId;

        return $this;
    }

    /**
     * Get proveedorId
     *
     * @return integer 
     */
    public function getproveedorId()
    {
        return $this->proveedorId;
    }

    /**
     * Set proveedorRazonSocial
     *
     * @param string $proveedorRazonSocial
     * @return Pedidos
     */
    public function setProveedorRazonSocial($proveedorRazonSocial)
    {
        $this->proveedorRazonSocial = $proveedorRazonSocial;

        return $this;
    }

    /**
     * Get proveedorRazonSocial
     *
     * @return string
     */
    public function getProveedorRazonSocial()
    {
        return $this->proveedorRazonSocial;
    }
    /**
     * Set eventoTitulo
     *
     * @param string $eventoTitulo
     * @return Pedidos
     */
    public function setEventoTitulo($eventoTitulo)
    {
        $this->eventoTitulo = $eventoTitulo;

        return $this;
    }

    /**
     * Get eventoTitulo
     *
     * @return string
     */
    public function getEventoTitulo()
    {
        return $this->eventoTitulo;
    }
    /**
     * Set tansferencistaNombre
     *
     * @param string $tansferencistaNombre
     * @return Pedidos
     */
    public function setTansferencistaNombre($tansferencistaNombre)
    {
        $this->tansferencistaNombre = $tansferencistaNombre;

        return $this;
    }

    /**
     * Get tansferencistaNombre
     *
     * @return string
     */
    public function getTansferencistaNombre()
    {
        return $this->tansferencistaNombre;
    }

}
