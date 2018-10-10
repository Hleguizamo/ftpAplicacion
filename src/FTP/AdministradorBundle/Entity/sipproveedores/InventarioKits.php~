<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * InventarioKits
 *
 * @ORM\Table(name="inventario_kits")
 * @ORM\Entity
 */
class InventarioKits
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
     * @ORM\Column(name="centro", type="integer", nullable=false)
     */
    private $centro;

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo", type="integer", nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=100, nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor", type="string", length=100, nullable=false)
     */
    private $proveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_copi_proveedor", type="string", length=3, nullable=false)
     */
    private $codCopiProveedor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tiempo", type="date", nullable=true)
     */
    private $tiempo;

    function getId() {
        return $this->id;
    }

    function getCentro() {
        return $this->centro;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getProveedor() {
        return $this->proveedor;
    }

    function getCodCopiProveedor() {
        return $this->codCopiProveedor;
    }

    function getTiempo() {
        return $this->tiempo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCentro($centro) {
        $this->centro = $centro;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setProveedor($proveedor) {
        $this->proveedor = $proveedor;
    }

    function setCodCopiProveedor($codCopiProveedor) {
        $this->codCopiProveedor = $codCopiProveedor;
    }

    function setTiempo(\DateTime $tiempo) {
        $this->tiempo = $tiempo;
    }


}
