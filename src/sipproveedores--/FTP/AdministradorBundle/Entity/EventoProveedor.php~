<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventoProveedor
 */
class EventoProveedor
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $aplicaA;

    /**
     * @var \FTP\AdministradorBundle\Entity\Evento
     */
    private $idEvento;

    /**
     * @var \FTP\AdministradorBundle\Entity\Proveedor
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
     * Set aplicaA
     *
     * @param string $aplicaA
     * @return EventoProveedor
     */
    public function setAplicaA($aplicaA)
    {
        $this->aplicaA = $aplicaA;
    
        return $this;
    }

    /**
     * Get aplicaA
     *
     * @return string 
     */
    public function getAplicaA()
    {
        return $this->aplicaA;
    }

    /**
     * Set idEvento
     *
     * @param \FTP\AdministradorBundle\Entity\Evento $idEvento
     * @return EventoProveedor
     */
    public function setIdEvento(\FTP\AdministradorBundle\Entity\Evento $idEvento = null)
    {
        $this->idEvento = $idEvento;
    
        return $this;
    }

    /**
     * Get idEvento
     *
     * @return \FTP\AdministradorBundle\Entity\Evento 
     */
    public function getIdEvento()
    {
        return $this->idEvento;
    }

    /**
     * Set idProveedor
     *
     * @param \FTP\AdministradorBundle\Entity\Proveedor $idProveedor
     * @return EventoProveedor
     */
    public function setIdProveedor(\FTP\AdministradorBundle\Entity\Proveedor $idProveedor = null)
    {
        $this->idProveedor = $idProveedor;
    
        return $this;
    }

    /**
     * Get idProveedor
     *
     * @return \FTP\AdministradorBundle\Entity\Proveedor 
     */
    public function getIdProveedor()
    {
        return $this->idProveedor;
    }
}
