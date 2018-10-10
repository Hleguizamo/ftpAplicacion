<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogProveedor
 *
 * @ORM\Table(name="log_proveedor")
 * @ORM\Entity
 */
class LogProveedor
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
     * @var \DateTime
     *
     * @ORM\Column(name="tiempo", type="datetime", nullable=true)
     */
    private $tiempo;

    /**
     * @var string
     *
     * @ORM\Column(name="actividad", type="string", length=255, nullable=false)
     */
    private $actividad;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=30, nullable=false)
     */
    private $ip;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_proveedor", type="bigint", nullable=false)
     */
    private $idProveedor;


}
