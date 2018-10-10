<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensajes
 *
 * @ORM\Table(name="mensajes", indexes={@ORM\Index(name="fk_admin_mensajes", columns={"id_administrador"})})
 * @ORM\Entity
 */
class Mensajes
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text", nullable=false)
     */
    private $texto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_activacion", type="datetime", nullable=true)
     */
    private $fechaActivacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_finalizacion", type="datetime", nullable=false)
     */
    private $fechaFinalizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255, nullable=false)
     */
    private $tipo;

    /**
     * @var \Administradores
     *
     * @ORM\ManyToOne(targetEntity="Administradores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_administrador", referencedColumnName="id")
     * })
     */
    private $idAdministrador;


}
