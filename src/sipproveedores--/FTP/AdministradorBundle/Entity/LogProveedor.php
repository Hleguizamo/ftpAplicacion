<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogProveedor
 */
class LogProveedor
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
     * @var integer
     */
    private $idProveedor;


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
     * @return LogProveedor
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
     * @return LogProveedor
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
     * @return LogProveedor
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
     * Set idProveedor
     *
     * @param integer $idProveedor
     * @return LogProveedor
     */
    public function setIdProveedor($idProveedor)
    {
        $this->idProveedor = $idProveedor;
    
        return $this;
    }

    /**
     * Get idProveedor
     *
     * @return integer 
     */
    public function getIdProveedor()
    {
        return $this->idProveedor;
    }
}
