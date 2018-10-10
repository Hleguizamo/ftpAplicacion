<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="evento", indexes={@ORM\Index(name="id_tipo_evento", columns={"id_tipo_evento"}), @ORM\Index(name="id_creador", columns={"id_creador"}), @ORM\Index(name="id_modificador", columns={"id_modificador"})})
 * @ORM\Entity
 */
class Evento
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
     * @ORM\Column(name="titulo", type="string", length=50, nullable=false)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=false)
     */
    private $descripcion;

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
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="datetime", nullable=false)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_finalizacion", type="datetime", nullable=false)
     */
    private $fechaFinalizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="aplica_a", type="string", length=45, nullable=false)
     */
    private $aplicaA;

    /**
     * @var \TipoEvento
     *
     * @ORM\ManyToOne(targetEntity="TipoEvento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tipo_evento", referencedColumnName="id")
     * })
     */
    private $idTipoEvento;

    /**
     * @var \Administradores
     *
     * @ORM\ManyToOne(targetEntity="Administradores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_creador", referencedColumnName="id")
     * })
     */
    private $idCreador;

    /**
     * @var \Administradores
     *
     * @ORM\ManyToOne(targetEntity="Administradores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_modificador", referencedColumnName="id")
     * })
     */
    private $idModificador;


}
