<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanCiudadDeptoCentro
 *
 * @ORM\Table(name="plan_ciudad_depto_centro", indexes={@ORM\Index(name="plan_id", columns={"plan_id"})})
 * @ORM\Entity
 */
class PlanCiudadDeptoCentro
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
     * @ORM\Column(name="ciudad", type="string", length=100, nullable=true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="departamento", type="string", length=100, nullable=true)
     */
    private $departamento;

    /**
     * @var integer
     *
     * @ORM\Column(name="centro", type="integer", nullable=true)
     */
    private $centro;

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
