<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Descontinuados
 *
 * @ORM\Table(name="descontinuados")
 * @ORM\Entity
 */
class Descontinuados
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
     * @ORM\Column(name="cod_copi_proveedor", type="string", length=3, nullable=false)
     */
    private $codCopiProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor", type="string", length=80, nullable=false)
     */
    private $proveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_producto", type="string", length=30, nullable=false)
     */
    private $codigoProducto;

    /**
     * @var string
     *
     * @ORM\Column(name="producto", type="string", length=100, nullable=false)
     */
    private $producto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha = '0000-00-00';


}
