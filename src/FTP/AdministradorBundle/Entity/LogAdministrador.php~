<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogAdministrador
 *
 * @ORM\Table(name="log_administrador")
 * @ORM\Entity
 */
class LogAdministrador
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
     * @ORM\Column(name="id_administrador", type="bigint", nullable=true)
     */
    private $idAdministrador;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tiempo", type="datetime", nullable=true)
     */
    private $tiempo;

    /**
     * @var string
     *
     * @ORM\Column(name="actividad", type="string", length=255, nullable=false)
     */
    private $actividad;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=30, nullable=true)
     */
    private $ip;



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
     * Set idAdministrador
     *
     * @param integer $idAdministrador
     * @return LogAdministrador
     */
    public function setIdAdministrador($idAdministrador)
    {
        $this->idAdministrador = $idAdministrador;
    
        return $this;
    }

    /**
     * Get idAdministrador
     *
     * @return integer 
     */
    public function getIdAdministrador()
    {
        return $this->idAdministrador;
    }

    /**
     * Set tiempo
     *
     * @param \DateTime $tiempo
     * @return LogAdministrador
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
     * @return LogAdministrador
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
     * @return LogAdministrador
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
}
