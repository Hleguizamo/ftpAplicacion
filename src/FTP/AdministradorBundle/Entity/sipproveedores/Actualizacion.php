<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actualizacion
 *
 * @ORM\Table(name="actualizacion")
 * @ORM\Entity
 */
class Actualizacion
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
     * @ORM\Column(name="fecha", type="string", length=50, nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="hora", type="string", length=50, nullable=false)
     */
    private $hora;


}
