<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DivisionTransferencista
 *
 * @ORM\Table(name="division_transferencista", indexes={@ORM\Index(name="proveedor_id", columns={"proveedor_id"}), @ORM\Index(name="fk_tranf_division", columns={"transferencista_id"})})
 * @ORM\Entity
 */
class DivisionTransferencista
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
     * @ORM\Column(name="fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="division", type="string", length=3, nullable=false)
     */
    private $division;

    /**
     * @var \Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     * })
     */
    private $proveedor;

    /**
     * @var \Transferencista
     *
     * @ORM\ManyToOne(targetEntity="Transferencista")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="transferencista_id", referencedColumnName="id")
     * })
     */
    private $transferencista;


}
