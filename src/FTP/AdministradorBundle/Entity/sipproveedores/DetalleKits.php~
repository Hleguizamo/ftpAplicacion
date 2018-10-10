<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleKits
 *
 * @ORM\Table(name="detalle_kits")
 * @ORM\Entity
 */
class DetalleKits
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
     * @ORM\Column(name="centro", type="string", length=80, nullable=true)
     */
    private $centro;

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo_kit", type="integer", nullable=false)
     */
    private $codigoKit;

    /**
     * @var integer
     *
     * @ORM\Column(name="descripcion", type="integer", nullable=true)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="integer", nullable=true)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_producto", type="string", length=80, nullable=true)
     */
    private $nombreProducto;

    /**
     * @var string
     *
     * @ORM\Column(name="campo2", type="string", length=80, nullable=true)
     */
    private $campo2;

    function getId() {
        return $this->id;
    }

    function getCentro() {
        return $this->centro;
    }

    function getCodigoKit() {
        return $this->codigoKit;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getNombreProducto() {
        return $this->nombreProducto;
    }

    function getCampo2() {
        return $this->campo2;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCentro($centro) {
        $this->centro = $centro;
    }

    function setCodigoKit($codigoKit) {
        $this->codigoKit = $codigoKit;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setNombreProducto($nombreProducto) {
        $this->nombreProducto = $nombreProducto;
    }

    function setCampo2($campo2) {
        $this->campo2 = $campo2;
    }



}
