<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transferencias
 *
 * @ORM\Table(name="transferencias")
 * @ORM\Entity
 */
class Transferencias
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nit_proveedor", type="string", length=30, nullable=false)
     */
    private $nitProveedor;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_transferencia", type="bigint", nullable=false)
     */
    private $numTransferencia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_trans", type="date", nullable=false)
     */
    private $fechaTrans;

    /**
     * @var string
     *
     * @ORM\Column(name="num_factura", type="string", length=30, nullable=false)
     */
    private $numFactura;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_factura", type="date", nullable=false)
     */
    private $fechaFactura = '0000-00-00';

    /**
     * @var integer
     *
     * @ORM\Column(name="centro", type="integer", nullable=false)
     */
    private $centro;

    /**
     * @var integer
     *
     * @ORM\Column(name="cod_drogueria", type="integer", nullable=false)
     */
    private $codDrogueria;

    /**
     * @var string
     *
     * @ORM\Column(name="drogueria", type="string", length=50, nullable=false)
     */
    private $drogueria;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=100, nullable=false)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="departamento", type="string", length=30, nullable=false)
     */
    private $departamento;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=30, nullable=false)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="posicion", type="string", length=30, nullable=false)
     */
    private $posicion;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor", type="string", length=100, nullable=false)
     */
    private $proveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="division", type="string", length=30, nullable=false)
     */
    private $division;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_material", type="string", length=30, nullable=false)
     */
    private $codMaterial;

    /**
     * @var string
     *
     * @ORM\Column(name="material", type="string", length=100, nullable=false)
     */
    private $material;

    /**
     * @var string
     *
     * @ORM\Column(name="lote", type="string", length=30, nullable=false)
     */
    private $lote;

    /**
     * @var integer
     *
     * @ORM\Column(name="cnt_trans", type="integer", nullable=false)
     */
    private $cntTrans;

    /**
     * @var float
     *
     * @ORM\Column(name="val_trans", type="float", precision=12, scale=2, nullable=false)
     */
    private $valTrans;

    /**
     * @var float
     *
     * @ORM\Column(name="cant_fact", type="float", precision=12, scale=2, nullable=false)
     */
    private $cantFact;

    /**
     * @var float
     *
     * @ORM\Column(name="val_fac", type="float", precision=12, scale=2, nullable=false)
     */
    private $valFac;

    /**
     * @var string
     *
     * @ORM\Column(name="transf", type="string", length=100, nullable=false)
     */
    private $transf;

    /**
     * @var string
     *
     * @ORM\Column(name="referencia", type="string", length=30, nullable=false)
     */
    private $referencia;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=30, nullable=false)
     */
    private $estado;


}
