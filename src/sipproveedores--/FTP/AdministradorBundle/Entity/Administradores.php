<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Administradores
 */
class Administradores
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
    private $usuario;

    /**
     * @var string
     */
    private $contrasena;

    /**
     * @var boolean
     */
    private $seguimiento;

    /**
     * @var string
     */
    private $email;

    /**
     * @var boolean
     */
    private $estado;

    /**
     * @var \DateTime
     */
    private $fechaClave;


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
     * @return Administradores
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
     * Set usuario
     *
     * @param string $usuario
     * @return Administradores
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    
        return $this;
    }

    /**
     * Get usuario
     *
     * @return string 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set contrasena
     *
     * @param string $contrasena
     * @return Administradores
     */
    public function setContrasena($contrasena)
    {
        $this->contrasena = $contrasena;
    
        return $this;
    }

    /**
     * Get contrasena
     *
     * @return string 
     */
    public function getContrasena()
    {
        return $this->contrasena;
    }

    /**
     * Set seguimiento
     *
     * @param boolean $seguimiento
     * @return Administradores
     */
    public function setSeguimiento($seguimiento)
    {
        $this->seguimiento = $seguimiento;
    
        return $this;
    }

    /**
     * Get seguimiento
     *
     * @return boolean 
     */
    public function getSeguimiento()
    {
        return $this->seguimiento;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Administradores
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return Administradores
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
     * Set fechaClave
     *
     * @param \DateTime $fechaClave
     * @return Administradores
     */
    public function setFechaClave($fechaClave)
    {
        $this->fechaClave = $fechaClave;
    
        return $this;
    }

    /**
     * Get fechaClave
     *
     * @return \DateTime 
     */
    public function getFechaClave()
    {
        return $this->fechaClave;
    }
}
