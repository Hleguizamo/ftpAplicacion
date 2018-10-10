<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conectados
 *
 * @ORM\Table(name="conectados", indexes={@ORM\Index(name="transferencista_id", columns={"transferencista_id"})})
 * @ORM\Entity
 */
class Conectados
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
     * @var integer
     *
     * @ORM\Column(name="tiempo", type="bigint", nullable=true)
     */
    private $tiempo;

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
