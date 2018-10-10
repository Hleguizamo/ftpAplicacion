<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Centros
 *
 * @ORM\Table(name="centros")
 * @ORM\Entity
 */
class Centros
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
     * @ORM\Column(name="numcentro", type="integer", nullable=false)
     */
    private $numcentro;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=60, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable", type="string", length=150, nullable=true)
     */
    private $responsable;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="txt_inventario", type="string", length=100, nullable=true)
     */
    private $txtInventario;

    /**
     * @var string
     *
     * @ORM\Column(name="txt_kits", type="string", length=100, nullable=true)
     */
    private $txtKits;

    /**
     * @var string
     *
     * @ORM\Column(name="txt_bonificacion", type="string", length=100, nullable=true)
     */
    private $txtBonificacion;

    /**
     * @var string
     *
     * @ORM\Column(name="txt_prepaks", type="string", length=250, nullable=false)
     */
    private $txtPrepaks;


}
