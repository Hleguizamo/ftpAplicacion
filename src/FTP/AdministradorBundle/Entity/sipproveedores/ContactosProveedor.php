<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContactosProveedor
 *
 * @ORM\Table(name="contactos_proveedor", indexes={@ORM\Index(name="proveedor_id", columns={"proveedor_id"}), @ORM\Index(name="subcategoria", columns={"subcategoria_id"}), @ORM\Index(name="categoria", columns={"categoria_id"})})
 * @ORM\Entity
 */
class ContactosProveedor
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
     * @var integer
     *
     * @ORM\Column(name="formulario", type="integer", nullable=false)
     */
    private $formulario;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion_subcategoria", type="string", length=40, nullable=true)
     */
    private $descripcionSubcategoria;

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
     * @ORM\Column(name="nombre_apellidos", type="string", length=120, nullable=true)
     */
    private $nombreApellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=60, nullable=true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_fijo", type="string", length=40, nullable=true)
     */
    private $telefonoFijo;

    /**
     * @var integer
     *
     * @ORM\Column(name="telefono_movil", type="bigint", nullable=true)
     */
    private $telefonoMovil;

    /**
     * @var \Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     * })
     */
    private $proveedor;

    /**
     * @var \FomularioCategotias
     *
     * @ORM\ManyToOne(targetEntity="FomularioCategotias")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
     * })
     */
    private $categoria;

    /**
     * @var \FomularioSubCategotias
     *
     * @ORM\ManyToOne(targetEntity="FomularioSubCategotias")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subcategoria_id", referencedColumnName="id")
     * })
     */
    private $subcategoria;


}
