<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Proveedor
 *
 * @ORM\Table(name="proveedor", indexes={@ORM\Index(name="codigo_copidrogas", columns={"codigo_copidrogas"}), @ORM\Index(name="razon_social", columns={"razon_social"})})
 * @ORM\Entity
 */
class Proveedor
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
     * @ORM\Column(name="razon_social", type="string", length=150, nullable=false)
     */
    private $razonSocial;

    /**
     * @var string
     *
     * @ORM\Column(name="nit", type="string", length=30, nullable=false)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_copidrogas", type="string", length=3, nullable=false)
     */
    private $codigoCopidrogas;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable", type="string", length=150, nullable=false)
     */
    private $responsable;

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
     * @ORM\Column(name="usuario", type="string", length=20, nullable=false)
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
     * @ORM\Column(name="clave1", type="string", length=100, nullable=true)
     */
    private $clave1;

    /**
     * @var string
     *
     * @ORM\Column(name="clave2", type="string", length=100, nullable=true)
     */
    private $clave2;

    /**
     * @var string
     *
     * @ORM\Column(name="clave3", type="string", length=100, nullable=true)
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
     * @ORM\Column(name="ultimo_acceso", type="date", nullable=false)
     */
    private $ultimoAcceso;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_convenio_comercial", type="integer", nullable=true)
     */
    private $idConvenioComercial;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=45, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=45, nullable=true)
     */
    private $ciudad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_modificacion", type="datetime", nullable=true)
     */
    private $fechaModificacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_orden_actualizacion", type="datetime", nullable=true)
     */
    private $fechaOrdenActualizacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_desbloqueo", type="datetime", nullable=true)
     */
    private $fechaDesbloqueo;

    /**
     * @var string
     *
     * @ORM\Column(name="representante_legal", type="string", length=80, nullable=true)
     */
    private $representanteLegal;

    /**
     * @var string
     *
     * @ORM\Column(name="email_representante_legal", type="string", length=120, nullable=true)
     */
    private $emailRepresentanteLegal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actualiza_contactos", type="boolean", nullable=true)
     */
    private $actualizaContactos = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_formularios", type="integer", nullable=true)
     */
    private $numeroFormularios = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actualiza_contactos_date", type="datetime", nullable=true)
     */
    private $actualizaContactosDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="actualiza_contactos_bloqueo", type="datetime", nullable=true)
     */
    private $actualizaContactosBloqueo;
    
    
    function getId() {
        return $this->id;
    }

    function getRazonSocial() {
        return $this->razonSocial;
    }

    function getNit() {
        return $this->nit;
    }

    function getCodigoCopidrogas() {
        return $this->codigoCopidrogas;
    }

    function getResponsable() {
        return $this->responsable;
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

    function getIdConvenioComercial() {
        return $this->idConvenioComercial;
    }

    function getFax() {
        return $this->fax;
    }

    function getCiudad() {
        return $this->ciudad;
    }

    function getFechaModificacion() {
        return $this->fechaModificacion;
    }

    function getFechaOrdenActualizacion() {
        return $this->fechaOrdenActualizacion;
    }

    function getFechaDesbloqueo() {
        return $this->fechaDesbloqueo;
    }

    function getRepresentanteLegal() {
        return $this->representanteLegal;
    }

    function getEmailRepresentanteLegal() {
        return $this->emailRepresentanteLegal;
    }

    function getActualizaContactos() {
        return $this->actualizaContactos;
    }

    function getNumeroFormularios() {
        return $this->numeroFormularios;
    }

    function getActualizaContactosDate() {
        return $this->actualizaContactosDate;
    }

    function getActualizaContactosBloqueo() {
        return $this->actualizaContactosBloqueo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setRazonSocial($razonSocial) {
        $this->razonSocial = $razonSocial;
    }

    function setNit($nit) {
        $this->nit = $nit;
    }

    function setCodigoCopidrogas($codigoCopidrogas) {
        $this->codigoCopidrogas = $codigoCopidrogas;
    }

    function setResponsable($responsable) {
        $this->responsable = $responsable;
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

    function setIdConvenioComercial($idConvenioComercial) {
        $this->idConvenioComercial = $idConvenioComercial;
    }

    function setFax($fax) {
        $this->fax = $fax;
    }

    function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    function setFechaModificacion(\DateTime $fechaModificacion) {
        $this->fechaModificacion = $fechaModificacion;
    }

    function setFechaOrdenActualizacion(\DateTime $fechaOrdenActualizacion) {
        $this->fechaOrdenActualizacion = $fechaOrdenActualizacion;
    }

    function setFechaDesbloqueo(\DateTime $fechaDesbloqueo) {
        $this->fechaDesbloqueo = $fechaDesbloqueo;
    }

    function setRepresentanteLegal($representanteLegal) {
        $this->representanteLegal = $representanteLegal;
    }

    function setEmailRepresentanteLegal($emailRepresentanteLegal) {
        $this->emailRepresentanteLegal = $emailRepresentanteLegal;
    }

    function setActualizaContactos($actualizaContactos) {
        $this->actualizaContactos = $actualizaContactos;
    }

    function setNumeroFormularios($numeroFormularios) {
        $this->numeroFormularios = $numeroFormularios;
    }

    function setActualizaContactosDate(\DateTime $actualizaContactosDate) {
        $this->actualizaContactosDate = $actualizaContactosDate;
    }

    function setActualizaContactosBloqueo(\DateTime $actualizaContactosBloqueo) {
        $this->actualizaContactosBloqueo = $actualizaContactosBloqueo;
    }



}
