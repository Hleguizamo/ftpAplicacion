<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatosProveedor
 *
 * @ORM\Table(name="datos_proveedor", indexes={@ORM\Index(name="proveedor_id", columns={"proveedor_id"})})
 * @ORM\Entity
 */
class DatosProveedor
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=false)
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_actualizacion", type="datetime", nullable=true)
     */
    private $fechaActualizacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="dias_para_actualizar", type="integer", nullable=false)
     */
    private $diasParaActualizar;

    /**
     * @var integer
     *
     * @ORM\Column(name="estado", type="integer", nullable=false)
     */
    private $estado;

    /**
     * @var integer
     *
     * @ORM\Column(name="dias_bloqueo_total", type="integer", nullable=false)
     */
    private $diasBloqueoTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="razon_social", type="string", length=80, nullable=true)
     */
    private $razonSocial;

    /**
     * @var string
     *
     * @ORM\Column(name="Nit", type="string", length=30, nullable=true)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=60, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_fijo", type="string", length=14, nullable=true)
     */
    private $telefonoFijo;

    /**
     * @var integer
     *
     * @ORM\Column(name="telefono_movil", type="integer", nullable=true)
     */
    private $telefonoMovil;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_apellidos_rep_legal", type="string", length=100, nullable=true)
     */
    private $nombreApellidosRepLegal;

    /**
     * @var string
     *
     * @ORM\Column(name="cargo_rep_legal", type="string", length=60, nullable=true)
     */
    private $cargoRepLegal;

    /**
     * @var integer
     *
     * @ORM\Column(name="genero_rep_legal", type="integer", nullable=true)
     */
    private $generoRepLegal;

    /**
     * @var string
     *
     * @ORM\Column(name="clasificacion", type="string", length=20, nullable=true)
     */
    private $clasificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="convenio", type="string", length=20, nullable=true)
     */
    private $convenio;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=10, nullable=true)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=16, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=30, nullable=true)
     */
    private $ciudad;

    /**
     * @var \Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     * })
     */
    private $proveedor;


}
