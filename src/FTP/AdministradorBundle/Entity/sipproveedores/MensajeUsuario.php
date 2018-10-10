<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MensajeUsuario
 *
 * @ORM\Table(name="mensaje_usuario", indexes={@ORM\Index(name="id_mensaje", columns={"id_mensaje"}), @ORM\Index(name="id_proveedor", columns={"id_proveedor"}), @ORM\Index(name="id_transferencista", columns={"id_transferencista"})})
 * @ORM\Entity
 */
class MensajeUsuario
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
     * @ORM\Column(name="fecha_leido", type="datetime", nullable=true)
     */
    private $fechaLeido;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
     */
    private $estado = '0';

    /**
     * @var \Mensajes
     *
     * @ORM\ManyToOne(targetEntity="Mensajes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_mensaje", referencedColumnName="id")
     * })
     */
    private $idMensaje;

    /**
     * @var \Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_proveedor", referencedColumnName="id")
     * })
     */
    private $idProveedor;

    /**
     * @var \Transferencista
     *
     * @ORM\ManyToOne(targetEntity="Transferencista")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_transferencista", referencedColumnName="id")
     * })
     */
    private $idTransferencista;


}
