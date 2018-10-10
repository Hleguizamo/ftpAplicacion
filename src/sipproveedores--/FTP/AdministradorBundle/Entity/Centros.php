<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Centros
 */
class Centros
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $numcentro;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $responsable;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $txtInventario;

    /**
     * @var string
     */
    private $txtKits;

    /**
     * @var string
     */
    private $txtBonificacion;

    /**
     * @var string
     */
    private $txtPrepaks;


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
     * Set numcentro
     *
     * @param integer $numcentro
     * @return Centros
     */
    public function setNumcentro($numcentro)
    {
        $this->numcentro = $numcentro;
    
        return $this;
    }

    /**
     * Get numcentro
     *
     * @return integer 
     */
    public function getNumcentro()
    {
        return $this->numcentro;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Centros
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
     * Set responsable
     *
     * @param string $responsable
     * @return Centros
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    
        return $this;
    }

    /**
     * Get responsable
     *
     * @return string 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Centros
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
     * Set txtInventario
     *
     * @param string $txtInventario
     * @return Centros
     */
    public function setTxtInventario($txtInventario)
    {
        $this->txtInventario = $txtInventario;
    
        return $this;
    }

    /**
     * Get txtInventario
     *
     * @return string 
     */
    public function getTxtInventario()
    {
        return $this->txtInventario;
    }

    /**
     * Set txtKits
     *
     * @param string $txtKits
     * @return Centros
     */
    public function setTxtKits($txtKits)
    {
        $this->txtKits = $txtKits;
    
        return $this;
    }

    /**
     * Get txtKits
     *
     * @return string 
     */
    public function getTxtKits()
    {
        return $this->txtKits;
    }

    /**
     * Set txtBonificacion
     *
     * @param string $txtBonificacion
     * @return Centros
     */
    public function setTxtBonificacion($txtBonificacion)
    {
        $this->txtBonificacion = $txtBonificacion;
    
        return $this;
    }

    /**
     * Get txtBonificacion
     *
     * @return string 
     */
    public function getTxtBonificacion()
    {
        return $this->txtBonificacion;
    }

    /**
     * Set txtPrepaks
     *
     * @param string $txtPrepaks
     * @return Centros
     */
    public function setTxtPrepaks($txtPrepaks)
    {
        $this->txtPrepaks = $txtPrepaks;
    
        return $this;
    }

    /**
     * Get txtPrepaks
     *
     * @return string 
     */
    public function getTxtPrepaks()
    {
        return $this->txtPrepaks;
    }
}
