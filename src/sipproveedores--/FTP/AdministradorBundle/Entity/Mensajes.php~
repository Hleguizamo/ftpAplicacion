<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensajes
 */
class Mensajes
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $texto;

    /**
     * @var \DateTime
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     */
    private $fechaActivacion;

    /**
     * @var \DateTime
     */
    private $fechaFinalizacion;

    /**
     * @var string
     */
    private $tipo;

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
     * Set nombre
     *
     * @param string $nombre
     * @return Mensajes
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set texto
     *
     * @param string $texto
     * @return Mensajes
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
    
        return $this;
    }

    /**
     * Get texto
     *
     * @return string 
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return Mensajes
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;
    
        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime 
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActivacion
     *
     * @param \DateTime $fechaActivacion
     * @return Mensajes
     */
    public function setFechaActivacion($fechaActivacion)
    {
        $this->fechaActivacion = $fechaActivacion;
    
        return $this;
    }

    /**
     * Get fechaActivacion
     *
     * @return \DateTime 
     */
    public function getFechaActivacion()
    {
        return $this->fechaActivacion;
    }

    /**
     * Set fechaFinalizacion
     *
     * @param \DateTime $fechaFinalizacion
     * @return Mensajes
     */
    public function setFechaFinalizacion($fechaFinalizacion)
    {
        $this->fechaFinalizacion = $fechaFinalizacion;
    
        return $this;
    }

    /**
     * Get fechaFinalizacion
     *
     * @return \DateTime 
     */
    public function getFechaFinalizacion()
    {
        return $this->fechaFinalizacion;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     * @return Mensajes
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return string 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set idAdministrador
     *
     * @param \FTP\AdministradorBundle\Entity\Administradores $idAdministrador
     * @return Mensajes
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
