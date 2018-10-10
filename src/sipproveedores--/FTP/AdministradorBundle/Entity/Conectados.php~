<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conectados
 */
class Conectados
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $tiempo;

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
     * Set tiempo
     *
     * @param integer $tiempo
     * @return Conectados
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;
    
        return $this;
    }

    /**
     * Get tiempo
     *
     * @return integer 
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set transferencista
     *
     * @param \FTP\AdministradorBundle\Entity\Transferencista $transferencista
     * @return Conectados
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
