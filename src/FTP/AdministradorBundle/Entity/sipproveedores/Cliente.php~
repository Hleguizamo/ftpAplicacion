<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cliente
 *
 * @ORM\Table(name="cliente", indexes={@ORM\Index(name="codigo", columns={"codigo"})})
 * @ORM\Entity
 */
class Cliente
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
     * @ORM\Column(name="zona", type="string", length=100, nullable=false)
     */
    private $zona;

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo", type="integer", nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="bloqueo_d", type="string", length=10, nullable=false)
     */
    private $bloqueoD;

    /**
     * @var string
     *
     * @ORM\Column(name="drogueria", type="string", length=100, nullable=false)
     */
    private $drogueria;

    /**
     * @var string
     *
     * @ORM\Column(name="nit", type="string", length=100, nullable=false)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="bloqueo_r", type="string", length=100, nullable=false)
     */
    private $bloqueoR;

    /**
     * @var string
     *
     * @ORM\Column(name="asociado", type="string", length=100, nullable=false)
     */
    private $asociado;

    /**
     * @var string
     *
     * @ORM\Column(name="nit_dv", type="string", length=100, nullable=false)
     */
    private $nitDv;

    /**
     * @var string
     *
     * @ORM\Column(name="direcion", type="string", length=100, nullable=false)
     */
    private $direcion;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_mun", type="string", length=100, nullable=false)
     */
    private $codMun;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=100, nullable=false)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="ruta", type="string", length=100, nullable=false)
     */
    private $ruta;

    /**
     * @var string
     *
     * @ORM\Column(name="un_geogra", type="string", length=100, nullable=false)
     */
    private $unGeogra;

    /**
     * @var string
     *
     * @ORM\Column(name="depto", type="string", length=100, nullable=false)
     */
    private $depto;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=100, nullable=false)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="centro", type="string", length=100, nullable=false)
     */
    private $centro;

    /**
     * @var string
     *
     * @ORM\Column(name="p_s", type="string", length=100, nullable=false)
     */
    private $pS;

    /**
     * @var string
     *
     * @ORM\Column(name="p_carga", type="string", length=100, nullable=false)
     */
    private $pCarga;

    /**
     * @var string
     *
     * @ORM\Column(name="diskette", type="string", length=100, nullable=false)
     */
    private $diskette;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cliente_tiempo", type="datetime", nullable=false)
     */
    private $clienteTiempo = '0000-00-00 00:00:00';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_asociado", type="string", length=100, nullable=false)
     */
    private $emailAsociado;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo_cliente", type="string", length=1, nullable=false)
     */
    private $tipoCliente;

    /**
     * @var string
     *
     * @ORM\Column(name="cupo_asociado", type="string", length=100, nullable=false)
     */
    private $cupoAsociado;

    function getId() {
        return $this->id;
    }

    function getZona() {
        return $this->zona;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getBloqueoD() {
        return $this->bloqueoD;
    }

    function getDrogueria() {
        return $this->drogueria;
    }

    function getNit() {
        return $this->nit;
    }

    function getBloqueoR() {
        return $this->bloqueoR;
    }

    function getAsociado() {
        return $this->asociado;
    }

    function getNitDv() {
        return $this->nitDv;
    }

    function getDirecion() {
        return $this->direcion;
    }

    function getCodMun() {
        return $this->codMun;
    }

    function getCiudad() {
        return $this->ciudad;
    }

    function getRuta() {
        return $this->ruta;
    }

    function getUnGeogra() {
        return $this->unGeogra;
    }

    function getDepto() {
        return $this->depto;
    }

    function getTelefono() {
        return $this->telefono;
    }

    function getCentro() {
        return $this->centro;
    }

    function getPS() {
        return $this->pS;
    }

    function getPCarga() {
        return $this->pCarga;
    }

    function getDiskette() {
        return $this->diskette;
    }

    function getClienteTiempo() {
        return $this->clienteTiempo;
    }

    function getEmail() {
        return $this->email;
    }

    function getEmailAsociado() {
        return $this->emailAsociado;
    }

    function getTipoCliente() {
        return $this->tipoCliente;
    }

    function getCupoAsociado() {
        return $this->cupoAsociado;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setZona($zona) {
        $this->zona = $zona;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setBloqueoD($bloqueoD) {
        $this->bloqueoD = $bloqueoD;
    }

    function setDrogueria($drogueria) {
        $this->drogueria = $drogueria;
    }

    function setNit($nit) {
        $this->nit = $nit;
    }

    function setBloqueoR($bloqueoR) {
        $this->bloqueoR = $bloqueoR;
    }

    function setAsociado($asociado) {
        $this->asociado = $asociado;
    }

    function setNitDv($nitDv) {
        $this->nitDv = $nitDv;
    }

    function setDirecion($direcion) {
        $this->direcion = $direcion;
    }

    function setCodMun($codMun) {
        $this->codMun = $codMun;
    }

    function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    function setRuta($ruta) {
        $this->ruta = $ruta;
    }

    function setUnGeogra($unGeogra) {
        $this->unGeogra = $unGeogra;
    }

    function setDepto($depto) {
        $this->depto = $depto;
    }

    function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    function setCentro($centro) {
        $this->centro = $centro;
    }

    function setPS($pS) {
        $this->pS = $pS;
    }

    function setPCarga($pCarga) {
        $this->pCarga = $pCarga;
    }

    function setDiskette($diskette) {
        $this->diskette = $diskette;
    }

    function setClienteTiempo(\DateTime $clienteTiempo) {
        $this->clienteTiempo = $clienteTiempo;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setEmailAsociado($emailAsociado) {
        $this->emailAsociado = $emailAsociado;
    }

    function setTipoCliente($tipoCliente) {
        $this->tipoCliente = $tipoCliente;
    }

    function setCupoAsociado($cupoAsociado) {
        $this->cupoAsociado = $cupoAsociado;
    }



}
