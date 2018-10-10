<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 */
class Log
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
    private $ipCliente;

    /**
     * @var integer
     */
    private $idProveedor;

    /**
     * @var integer
     */
    private $idTransferencista;


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
     * @return Log
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
     * @return Log
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
     * Set ipCliente
     *
     * @param string $ipCliente
     * @return Log
     */
    public function setIpCliente($ipCliente)
    {
        $this->ipCliente = $ipCliente;
    
        return $this;
    }

    /**
     * Get ipCliente
     *
     * @return string 
     */
    public function getIpCliente()
    {
        return $this->ipCliente;
    }

    /**
     * Set idProveedor
     *
     * @param integer $idProveedor
     * @return Log
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

    /**
     * Set idTransferencista
     *
     * @param integer $idTransferencista
     * @return Log
     */
    public function setIdTransferencista($idTransferencista)
    {
        $this->idTransferencista = $idTransferencista;
    
        return $this;
    }

    /**
     * Get idTransferencista
     *
     * @return integer 
     */
    public function getIdTransferencista()
    {
        return $this->idTransferencista;
    }
}
