<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetallePrepacks
 *
 * @ORM\Table(name="detalle_prepacks")
 * @ORM\Entity
 */
class DetallePrepacks
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
     * @ORM\Column(name="centro", type="smallint", nullable=false)
     */
    private $centro;

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo_prepack", type="integer", nullable=false)
     */
    private $codigoPrepack;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=true)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad", type="smallint", nullable=false)
     */
    private $cantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo_producto", type="integer", nullable=true)
     */
    private $codigoProducto;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_producto", type="string", length=100, nullable=true)
     */
    private $nombreProducto;

    /**
     * @var integer
     *
     * @ORM\Column(name="campo1", type="integer", nullable=false)
     */
    private $campo1;

    /**
     * @var integer
     *
     * @ORM\Column(name="campo2", type="integer", nullable=false)
     */
    private $campo2;

    function getId() {
        return $this->id;
    }

    function getCentro() {
        return $this->centro;
    }

    function getCodigoPrepack() {
        return $this->codigoPrepack;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getCodigoProducto() {
        return $this->codigoProducto;
    }

    function getNombreProducto() {
        return $this->nombreProducto;
    }

    function getCampo1() {
        return $this->campo1;
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

    function setCodigoPrepack($codigoPrepack) {
        $this->codigoPrepack = $codigoPrepack;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setCodigoProducto($codigoProducto) {
        $this->codigoProducto = $codigoProducto;
    }

    function setNombreProducto($nombreProducto) {
        $this->nombreProducto = $nombreProducto;
    }

    function setCampo1($campo1) {
        $this->campo1 = $campo1;
    }

    function setCampo2($campo2) {
        $this->campo2 = $campo2;
    }


}
