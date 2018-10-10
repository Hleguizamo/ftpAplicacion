<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DivisionTransferencista
 */
class DivisionTransferencista
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var string
     */
    private $division;

    /**
     * @var \FTP\AdministradorBundle\Entity\Proveedor
     */
    private $proveedor;

    /**
     * @var \FTP\AdministradorBundle\Entity\Transferencista
     */
    private $transferencista;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return DivisionTransferencista
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set division
     *
     * @param string $division
     * @return DivisionTransferencista
     */
    public function setDivision($division)
    {
        $this->division = $division;
    
        return $this;
    }

    /**
     * Get division
     *
     * @return string 
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Set proveedor
     *
     * @param \FTP\AdministradorBundle\Entity\Proveedor $proveedor
     * @return DivisionTransferencista
     */
    public function setProveedor(\FTP\AdministradorBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \FTP\AdministradorBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set transferencista
     *
     * @param \FTP\AdministradorBundle\Entity\Transferencista $transferencista
     * @return DivisionTransferencista
     */
    public function setTransferencista(\FTP\AdministradorBundle\Entity\Transferencista $transferencista = null)
    {
        $this->transferencista = $transferencista;
    
        return $this;
    }

    /**
     * Get transferencista
     *
     * @return \FTP\AdministradorBundle\Entity\Transferencista 
     */
    public function getTransferencista()
    {
        return $this->transferencista;
    }
}
