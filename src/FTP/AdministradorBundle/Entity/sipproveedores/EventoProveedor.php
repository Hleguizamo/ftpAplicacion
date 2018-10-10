<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoProveedor
 *
 * @ORM\Table(name="evento_proveedor", indexes={@ORM\Index(name="id_evento", columns={"id_evento"}), @ORM\Index(name="id_proveedor", columns={"id_proveedor"})})
 * @ORM\Entity
 */
class EventoProveedor
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
     * @ORM\Column(name="aplica_a", type="string", length=255, nullable=false)
     */
    private $aplicaA;

    /**
     * @var \Evento
     *
     * @ORM\ManyToOne(targetEntity="Evento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_evento", referencedColumnName="id")
     * })
     */
    private $idEvento;

    /**
     * @var \Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_proveedor", referencedColumnName="id")
     * })
     */
    private $idProveedor;


}
