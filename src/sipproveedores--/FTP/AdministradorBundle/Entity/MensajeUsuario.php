<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MensajeUsuario
 */
class MensajeUsuario
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fechaLeido;

    /**
     * @var boolean
     */
    private $estado;

    /**
     * @var \FTP\AdministradorBundle\Entity\Mensajes
     */
    private $idMensaje;

    /**
     * @var \FTP\AdministradorBundle\Entity\Proveedor
     */
    private $idProveedor;

    /**
     * @var \FTP\AdministradorBundle\Entity\Transferencista
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
     * Set fechaLeido
     *
     * @param \DateTime $fechaLeido
     * @return MensajeUsuario
     */
    public function setFechaLeido($fechaLeido)
    {
        $this->fechaLeido = $fechaLeido;
    
        return $this;
    }

    /**
     * Get fechaLeido
     *
     * @return \DateTime 
     */
    public function getFechaLeido()
    {
        return $this->fechaLeido;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return MensajeUsuario
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set idMensaje
     *
     * @param \FTP\AdministradorBundle\Entity\Mensajes $idMensaje
     * @return MensajeUsuario
     */
    public function setIdMensaje(\FTP\AdministradorBundle\Entity\Mensajes $idMensaje = null)
    {
        $this->idMensaje = $idMensaje;
    
        return $this;
    }

    /**
     * Get idMensaje
     *
     * @return \FTP\AdministradorBundle\Entity\Mensajes 
     */
    public function getIdMensaje()
    {
        return $this->idMensaje;
    }

    /**
     * Set idProveedor
     *
     * @param \FTP\AdministradorBundle\Entity\Proveedor $idProveedor
     * @return MensajeUsuario
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

    /**
     * Set idTransferencista
     *
     * @param \FTP\AdministradorBundle\Entity\Transferencista $idTransferencista
     * @return MensajeUsuario
     */
    public function setIdTransferencista(\FTP\AdministradorBundle\Entity\Transferencista $idTransferencista = null)
    {
        $this->idTransferencista = $idTransferencista;
    
        return $this;
    }

    /**
     * Get idTransferencista
     *
     * @return \FTP\AdministradorBundle\Entity\Transferencista 
     */
    public function getIdTransferencista()
    {
        return $this->idTransferencista;
    }
}
