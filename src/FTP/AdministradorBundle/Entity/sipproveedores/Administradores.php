<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Administradores
 *
 * @ORM\Table(name="administradores", uniqueConstraints={@ORM\UniqueConstraint(name="usuario", columns={"usuario"})})
 * @ORM\Entity
 */
class Administradores
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
     * @ORM\Column(name="nombre", type="string", length=80, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=30, nullable=false)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="contrasena", type="string", length=30, nullable=false)
     */
    private $contrasena;

    /**
     * @var boolean
     *
     * @ORM\Column(name="seguimiento", type="boolean", nullable=true)
     */
    private $seguimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_clave", type="date", nullable=false)
     */
    private $fechaClave;


}
