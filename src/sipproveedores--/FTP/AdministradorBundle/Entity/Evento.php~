<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 */
class Evento
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $titulo;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var \DateTime
     */
    private $fechaCreado;

    /**
     * @var \DateTime
     */
    private $fechaModificado;

    /**
     * @var boolean
     */
    private $estado;

    /**
     * @var \DateTime
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     */
    private $fechaFinalizacion;

    /**
     * @var string
     */
    private $aplicaA;

    /**
     * @var \FTP\AdministradorBundle\Entity\TipoEvento
     */
    private $idTipoEvento;

    /**
     * @var \FTP\AdministradorBundle\Entity\Administradores
     */
    private $idCreador;

    /**
     * @var \FTP\AdministradorBundle\Entity\Administradores
     */
    private $idModificador;


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
     * Set titulo
     *
     * @param string $titulo
     * @return Evento
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Evento
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return Evento
     */
    public function setFechaCreado($fechaCreado)
    {
        $this->fechaCreado = $fechaCreado;
    
        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime 
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * Set fechaModificado
     *
     * @param \DateTime $fechaModificado
     * @return Evento
     */
    public function setFechaModificado($fechaModificado)
    {
        $this->fechaModificado = $fechaModificado;
    
        return $this;
    }

    /**
     * Get fechaModificado
     *
     * @return \DateTime 
     */
    public function getFechaModificado()
    {
        return $this->fechaModificado;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return Evento
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
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     * @return Evento
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    
        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime 
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFinalizacion
     *
     * @param \DateTime $fechaFinalizacion
     * @return Evento
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
     * Set aplicaA
     *
     * @param string $aplicaA
     * @return Evento
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
     * Set idTipoEvento
     *
     * @param \FTP\AdministradorBundle\Entity\TipoEvento $idTipoEvento
     * @return Evento
     */
    public function setIdTipoEvento(\FTP\AdministradorBundle\Entity\TipoEvento $idTipoEvento = null)
    {
        $this->idTipoEvento = $idTipoEvento;
    
        return $this;
    }

    /**
     * Get idTipoEvento
     *
     * @return \FTP\AdministradorBundle\Entity\TipoEvento 
     */
    public function getIdTipoEvento()
    {
        return $this->idTipoEvento;
    }

    /**
     * Set idCreador
     *
     * @param \FTP\AdministradorBundle\Entity\Administradores $idCreador
     * @return Evento
     */
    public function setIdCreador(\FTP\AdministradorBundle\Entity\Administradores $idCreador = null)
    {
        $this->idCreador = $idCreador;
    
        return $this;
    }

    /**
     * Get idCreador
     *
     * @return \FTP\AdministradorBundle\Entity\Administradores 
     */
    public function getIdCreador()
    {
        return $this->idCreador;
    }

    /**
     * Set idModificador
     *
     * @param \FTP\AdministradorBundle\Entity\Administradores $idModificador
     * @return Evento
     */
    public function setIdModificador(\FTP\AdministradorBundle\Entity\Administradores $idModificador = null)
    {
        $this->idModificador = $idModificador;
    
        return $this;
    }

    /**
     * Get idModificador
     *
     * @return \FTP\AdministradorBundle\Entity\Administradores 
     */
    public function getIdModificador()
    {
        return $this->idModificador;
    }
}
