<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogAdmin
 */
class LogAdmin
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $tiempo;

    /**
     * @var string
     */
    private $actividad;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var \FTP\AdministradorBundle\Entity\Administradores
     */
    private $idAdministrador;


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
     * Set tiempo
     *
     * @param \DateTime $tiempo
     * @return LogAdmin
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;
    
        return $this;
    }

    /**
     * Get tiempo
     *
     * @return \DateTime 
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set actividad
     *
     * @param string $actividad
     * @return LogAdmin
     */
    public function setActividad($actividad)
    {
        $this->actividad = $actividad;
    
        return $this;
    }

    /**
     * Get actividad
     *
     * @return string 
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return LogAdmin
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set idAdministrador
     *
     * @param \FTP\AdministradorBundle\Entity\Administradores $idAdministrador
     * @return LogAdmin
     */
    public function setIdAdministrador(\FTP\AdministradorBundle\Entity\Administradores $idAdministrador = null)
    {
        $this->idAdministrador = $idAdministrador;
    
        return $this;
    }

    /**
     * Get idAdministrador
     *
     * @return \FTP\AdministradorBundle\Entity\Administradores 
     */
    public function getIdAdministrador()
    {
        return $this->idAdministrador;
    }
}
