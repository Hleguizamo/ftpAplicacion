<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transferencista
 *
 * @ORM\Table(name="transferencista", indexes={@ORM\Index(name="usuario", columns={"usuario"}), @ORM\Index(name="id_proveedor", columns={"id_proveedor"}), @ORM\Index(name="nombre", columns={"nombre"})})
 * @ORM\Entity
 */
class Transferencista
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
     * @ORM\Column(name="nombre", type="string", length=150, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=30, nullable=false)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=30, nullable=false)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="clave", type="string", length=100, nullable=false)
     */
    private $clave;

    /**
     * @var string
     *
     * @ORM\Column(name="clave1", type="string", length=100, nullable=false)
     */
    private $clave1;

    /**
     * @var string
     *
     * @ORM\Column(name="clave2", type="string", length=100, nullable=false)
     */
    private $clave2;

    /**
     * @var string
     *
     * @ORM\Column(name="clave3", type="string", length=100, nullable=false)
     */
    private $clave3;

    /**
     * @var string
     *
     * @ORM\Column(name="clave_activa", type="string", length=6, nullable=false)
     */
    private $claveActiva;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cambio_clave", type="date", nullable=false)
     */
    private $cambioClave;

    /**
     * @var string
     *
     * @ORM\Column(name="ultima_ip", type="string", length=30, nullable=false)
     */
    private $ultimaIp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ultimo_acceso", type="datetime", nullable=false)
     */
    private $ultimoAcceso = '0000-00-00 00:00:00';

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var \Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_proveedor", referencedColumnName="id")
     * })
     */
    private $idProveedor;
    
    function getId() {
        return $this->id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getEmail() {
        return $this->email;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getClave() {
        return $this->clave;
    }

    function getClave1() {
        return $this->clave1;
    }

    function getClave2() {
        return $this->clave2;
    }

    function getClave3() {
        return $this->clave3;
    }

    function getClaveActiva() {
        return $this->claveActiva;
    }

    function getCambioClave() {
        return $this->cambioClave;
    }

    function getUltimaIp() {
        return $this->ultimaIp;
    }

    function getUltimoAcceso() {
        return $this->ultimoAcceso;
    }

    function getEstado() {
        return $this->estado;
    }

    function getIdProveedor() {
        return $this->idProveedor;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setClave1($clave1) {
        $this->clave1 = $clave1;
    }

    function setClave2($clave2) {
        $this->clave2 = $clave2;
    }

    function setClave3($clave3) {
        $this->clave3 = $clave3;
    }

    function setClaveActiva($claveActiva) {
        $this->claveActiva = $claveActiva;
    }

    function setCambioClave(\DateTime $cambioClave) {
        $this->cambioClave = $cambioClave;
    }

    function setUltimaIp($ultimaIp) {
        $this->ultimaIp = $ultimaIp;
    }

    function setUltimoAcceso(\DateTime $ultimoAcceso) {
        $this->ultimoAcceso = $ultimoAcceso;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setIdProveedor(Proveedor $idProveedor) {
        $this->idProveedor = $idProveedor;
    }



}
