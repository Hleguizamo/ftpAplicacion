<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanCiudadDeptoCentro
 */
class PlanCiudadDeptoCentro
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $ciudad;

    /**
     * @var string
     */
    private $departamento;

    /**
     * @var integer
     */
    private $centro;

    /**
     * @var \FTP\AdministradorBundle\Entity\Plan
     */
    private $plan;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     * @return PlanCiudadDeptoCentro
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    
        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set departamento
     *
     * @param string $departamento
     * @return PlanCiudadDeptoCentro
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    
        return $this;
    }

    /**
     * Get departamento
     *
     * @return string 
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set centro
     *
     * @param integer $centro
     * @return PlanCiudadDeptoCentro
     */
    public function setCentro($centro)
    {
        $this->centro = $centro;
    
        return $this;
    }

    /**
     * Get centro
     *
     * @return integer 
     */
    public function getCentro()
    {
        return $this->centro;
    }

    /**
     * Set plan
     *
     * @param \FTP\AdministradorBundle\Entity\Plan $plan
     * @return PlanCiudadDeptoCentro
     */
    public function setPlan(\FTP\AdministradorBundle\Entity\Plan $plan = null)
    {
        $this->plan = $plan;
    
        return $this;
    }

    /**
     * Get plan
     *
     * @return \FTP\AdministradorBundle\Entity\Plan 
     */
    public function getPlan()
    {
        return $this->plan;
    }
}
