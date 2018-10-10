<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plan
 *
 * @ORM\Table(name="plan", indexes={@ORM\Index(name="crea_administrador_id", columns={"crea_administrador_id"}), @ORM\Index(name="modifica_administrador_id", columns={"modifica_administrador_id"})})
 * @ORM\Entity
 */
class Plan
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
     * @ORM\Column(name="nombre", type="string", length=60, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=false)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="icono", type="string", length=50, nullable=true)
     */
    private $icono;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tipo", type="boolean", nullable=false)
     */
    private $tipo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_plan", type="string", length=20, nullable=false)
     */
    private $codigoPlan;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero_predidos", type="integer", nullable=true)
     */
    private $numeroPredidos;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creado", type="datetime", nullable=false)
     */
    private $fechaCreado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_modificado", type="datetime", nullable=true)
     */
    private $fechaModificado;

    /**
     * @var \Administradores
     *
     * @ORM\ManyToOne(targetEntity="Administradores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="crea_administrador_id", referencedColumnName="id")
     * })
     */
    private $creaAdministrador;

    /**
     * @var \Administradores
     *
     * @ORM\ManyToOne(targetEntity="Administradores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modifica_administrador_id", referencedColumnName="id")
     * })
     */
    private $modificaAdministrador;


}
