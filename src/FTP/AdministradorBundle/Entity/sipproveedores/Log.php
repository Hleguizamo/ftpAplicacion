<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table(name="log")
 * @ORM\Entity
 */
class Log
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
     * @ORM\Column(name="ip_cliente", type="string", length=30, nullable=true)
     */
    private $ipCliente;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_proveedor", type="bigint", nullable=false)
     */
    private $idProveedor;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_transferencista", type="bigint", nullable=false)
     */
    private $idTransferencista;


}
