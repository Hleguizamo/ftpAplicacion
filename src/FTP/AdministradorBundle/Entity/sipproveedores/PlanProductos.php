<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanProductos
 *
 * @ORM\Table(name="plan_productos", indexes={@ORM\Index(name="inventario_id_idx", columns={"material"}), @ORM\Index(name="plan_id_idx", columns={"plan_id"})})
 * @ORM\Entity
 */
class PlanProductos
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
     * @ORM\Column(name="material", type="integer", nullable=false)
     */
    private $material;

    /**
     * @var integer
     *
     * @ORM\Column(name="centro", type="integer", nullable=true)
     */
    private $centro;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=50, nullable=true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="departamento", type="string", length=50, nullable=true)
     */
    private $departamento;

    /**
     * @var integer
     *
     * @ORM\Column(name="cod_ciudad", type="integer", nullable=true)
     */
    private $codCiudad;

    /**
     * @var integer
     *
     * @ORM\Column(name="cod_departamento", type="integer", nullable=true)
     */
    private $codDepartamento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_inicio", type="date", nullable=true)
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_final", type="date", nullable=true)
     */
    private $fechaFinal;

    /**
     * @var \Plan
     *
     * @ORM\ManyToOne(targetEntity="Plan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plan_id", referencedColumnName="id")
     * })
     */
    private $plan;


}
