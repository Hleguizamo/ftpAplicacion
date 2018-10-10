<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NoEncontrados
 *
 * @ORM\Table(name="no_encontrados")
 * @ORM\Entity
 */
class NoEncontrados
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
     * @ORM\Column(name="codigo_asociado", type="integer", nullable=false)
     */
    private $codigoAsociado;

    /**
     * @var integer
     *
     * @ORM\Column(name="centro", type="smallint", nullable=false)
     */
    private $centro;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_asociado", type="string", length=100, nullable=false)
     */
    private $nombreAsociado;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo", type="string", length=50, nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="producto", type="string", length=50, nullable=false)
     */
    private $producto;

    /**
     * @var string
     *
     * @ORM\Column(name="cantidad", type="string", length=50, nullable=false)
     */
    private $cantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_barras", type="string", length=50, nullable=false)
     */
    private $codigoBarras;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tiempo", type="datetime", nullable=false)
     */
    private $tiempo;


}
