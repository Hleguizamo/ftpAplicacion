<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Actualizacion
 */
class Actualizacion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $fecha;

    /**
     * @var string
     */
    private $hora;


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
     * @param string $fecha
     * @return Actualizacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return string 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set hora
     *
     * @param string $hora
     * @return Actualizacion
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    
        return $this;
    }

    /**
     * Get hora
     *
     * @return string 
     */
    public function getHora()
    {
        return $this->hora;
    }
}
