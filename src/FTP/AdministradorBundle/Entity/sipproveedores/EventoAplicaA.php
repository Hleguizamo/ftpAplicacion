<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoAplicaA
 *
 * @ORM\Table(name="evento_aplica_a")
 * @ORM\Entity
 */
class EventoAplicaA
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
     * @ORM\Column(name="id_usuario", type="integer", nullable=false)
     */
    private $idUsuario;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_proveedor", type="integer", nullable=false)
     */
    private $idProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="aplica_a", type="string", length=255, nullable=false)
     */
    private $aplicaA;


}
