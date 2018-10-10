<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContactosProveedor
 */
class ContactosProveedor
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $formulario;

    /**
     * @var string
     */
    private $descripcionSubcategoria;

    /**
     * @var \DateTime
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     */
    private $fechaActualizacion;

    /**
     * @var integer
     */
    private $diasParaActualizar;

    /**
     * @var integer
     */
    private $estado;

    /**
     * @var integer
     */
    private $diasBloqueoTotal;

    /**
     * @var string
     */
    private $nombreApellidos;

    /**
     * @var string
     */
    private $ciudad;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $telefonoFijo;

    /**
     * @var integer
     */
    private $telefonoMovil;

    /**
     * @var \FTP\AdministradorBundle\Entity\Proveedor
     */
    private $proveedor;

    /**
     * @var \FTP\AdministradorBundle\Entity\FomularioCategotias
     */
    private $categoria;

    /**
     * @var \FTP\AdministradorBundle\Entity\FomularioSubCategotias
     */
    private $subcategoria;


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
     * Set formulario
     *
     * @param integer $formulario
     * @return ContactosProveedor
     */
    public function setFormulario($formulario)
    {
        $this->formulario = $formulario;
    
        return $this;
    }

    /**
     * Get formulario
     *
     * @return integer 
     */
    public function getFormulario()
    {
        return $this->formulario;
    }

    /**
     * Set descripcionSubcategoria
     *
     * @param string $descripcionSubcategoria
     * @return ContactosProveedor
     */
    public function setDescripcionSubcategoria($descripcionSubcategoria)
    {
        $this->descripcionSubcategoria = $descripcionSubcategoria;
    
        return $this;
    }

    /**
     * Get descripcionSubcategoria
     *
     * @return string 
     */
    public function getDescripcionSubcategoria()
    {
        return $this->descripcionSubcategoria;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return ContactosProveedor
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
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     * @return ContactosProveedor
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;
    
        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime 
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set diasParaActualizar
     *
     * @param integer $diasParaActualizar
     * @return ContactosProveedor
     */
    public function setDiasParaActualizar($diasParaActualizar)
    {
        $this->diasParaActualizar = $diasParaActualizar;
    
        return $this;
    }

    /**
     * Get diasParaActualizar
     *
     * @return integer 
     */
    public function getDiasParaActualizar()
    {
        return $this->diasParaActualizar;
    }

    /**
     * Set estado
     *
     * @param integer $estado
     * @return ContactosProveedor
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return integer 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set diasBloqueoTotal
     *
     * @param integer $diasBloqueoTotal
     * @return ContactosProveedor
     */
    public function setDiasBloqueoTotal($diasBloqueoTotal)
    {
        $this->diasBloqueoTotal = $diasBloqueoTotal;
    
        return $this;
    }

    /**
     * Get diasBloqueoTotal
     *
     * @return integer 
     */
    public function getDiasBloqueoTotal()
    {
        return $this->diasBloqueoTotal;
    }

    /**
     * Set nombreApellidos
     *
     * @param string $nombreApellidos
     * @return ContactosProveedor
     */
    public function setNombreApellidos($nombreApellidos)
    {
        $this->nombreApellidos = $nombreApellidos;
    
        return $this;
    }

    /**
     * Get nombreApellidos
     *
     * @return string 
     */
    public function getNombreApellidos()
    {
        return $this->nombreApellidos;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     * @return ContactosProveedor
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    
        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return ContactosProveedor
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
     * Set telefonoFijo
     *
     * @param string $telefonoFijo
     * @return ContactosProveedor
     */
    public function setTelefonoFijo($telefonoFijo)
    {
        $this->telefonoFijo = $telefonoFijo;
    
        return $this;
    }

    /**
     * Get telefonoFijo
     *
     * @return string 
     */
    public function getTelefonoFijo()
    {
        return $this->telefonoFijo;
    }

    /**
     * Set telefonoMovil
     *
     * @param integer $telefonoMovil
     * @return ContactosProveedor
     */
    public function setTelefonoMovil($telefonoMovil)
    {
        $this->telefonoMovil = $telefonoMovil;
    
        return $this;
    }

    /**
     * Get telefonoMovil
     *
     * @return integer 
     */
    public function getTelefonoMovil()
    {
        return $this->telefonoMovil;
    }

    /**
     * Set proveedor
     *
     * @param \FTP\AdministradorBundle\Entity\Proveedor $proveedor
     * @return ContactosProveedor
     */
    public function setProveedor(\FTP\AdministradorBundle\Entity\Proveedor $proveedor = null)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return \FTP\AdministradorBundle\Entity\Proveedor 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set categoria
     *
     * @param \FTP\AdministradorBundle\Entity\FomularioCategotias $categoria
     * @return ContactosProveedor
     */
    public function setCategoria(\FTP\AdministradorBundle\Entity\FomularioCategotias $categoria = null)
    {
        $this->categoria = $categoria;
    
        return $this;
    }

    /**
     * Get categoria
     *
     * @return \FTP\AdministradorBundle\Entity\FomularioCategotias 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set subcategoria
     *
     * @param \FTP\AdministradorBundle\Entity\FomularioSubCategotias $subcategoria
     * @return ContactosProveedor
     */
    public function setSubcategoria(\FTP\AdministradorBundle\Entity\FomularioSubCategotias $subcategoria = null)
    {
        $this->subcategoria = $subcategoria;
    
        return $this;
    }

    /**
     * Get subcategoria
     *
     * @return \FTP\AdministradorBundle\Entity\FomularioSubCategotias 
     */
    public function getSubcategoria()
    {
        return $this->subcategoria;
    }
}
