<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatosProveedor
 */
class DatosProveedor
{
    /**
     * @var integer
     */
    private $id;

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
    private $razonSocial;

    /**
     * @var string
     */
    private $nit;

    /**
     * @var string
     */
    private $direccion;

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
     * @var string
     */
    private $nombreApellidosRepLegal;

    /**
     * @var string
     */
    private $cargoRepLegal;

    /**
     * @var integer
     */
    private $generoRepLegal;

    /**
     * @var string
     */
    private $clasificacion;

    /**
     * @var string
     */
    private $convenio;

    /**
     * @var string
     */
    private $codigo;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $ciudad;

    /**
     * @var \FTP\AdministradorBundle\Entity\Proveedor
     */
    private $proveedor;


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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     * @return DatosProveedor
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
     * @return DatosProveedor
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
     * @return DatosProveedor
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
     * @return DatosProveedor
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
     * @return DatosProveedor
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
     * Set razonSocial
     *
     * @param string $razonSocial
     * @return DatosProveedor
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = $razonSocial;
    
        return $this;
    }

    /**
     * Get razonSocial
     *
     * @return string 
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set nit
     *
     * @param string $nit
     * @return DatosProveedor
     */
    public function setNit($nit)
    {
        $this->nit = $nit;
    
        return $this;
    }

    /**
     * Get nit
     *
     * @return string 
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return DatosProveedor
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return DatosProveedor
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
     * @return DatosProveedor
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
     * @return DatosProveedor
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
     * Set nombreApellidosRepLegal
     *
     * @param string $nombreApellidosRepLegal
     * @return DatosProveedor
     */
    public function setNombreApellidosRepLegal($nombreApellidosRepLegal)
    {
        $this->nombreApellidosRepLegal = $nombreApellidosRepLegal;
    
        return $this;
    }

    /**
     * Get nombreApellidosRepLegal
     *
     * @return string 
     */
    public function getNombreApellidosRepLegal()
    {
        return $this->nombreApellidosRepLegal;
    }

    /**
     * Set cargoRepLegal
     *
     * @param string $cargoRepLegal
     * @return DatosProveedor
     */
    public function setCargoRepLegal($cargoRepLegal)
    {
        $this->cargoRepLegal = $cargoRepLegal;
    
        return $this;
    }

    /**
     * Get cargoRepLegal
     *
     * @return string 
     */
    public function getCargoRepLegal()
    {
        return $this->cargoRepLegal;
    }

    /**
     * Set generoRepLegal
     *
     * @param integer $generoRepLegal
     * @return DatosProveedor
     */
    public function setGeneroRepLegal($generoRepLegal)
    {
        $this->generoRepLegal = $generoRepLegal;
    
        return $this;
    }

    /**
     * Get generoRepLegal
     *
     * @return integer 
     */
    public function getGeneroRepLegal()
    {
        return $this->generoRepLegal;
    }

    /**
     * Set clasificacion
     *
     * @param string $clasificacion
     * @return DatosProveedor
     */
    public function setClasificacion($clasificacion)
    {
        $this->clasificacion = $clasificacion;
    
        return $this;
    }

    /**
     * Get clasificacion
     *
     * @return string 
     */
    public function getClasificacion()
    {
        return $this->clasificacion;
    }

    /**
     * Set convenio
     *
     * @param string $convenio
     * @return DatosProveedor
     */
    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;
    
        return $this;
    }

    /**
     * Get convenio
     *
     * @return string 
     */
    public function getConvenio()
    {
        return $this->convenio;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return DatosProveedor
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return DatosProveedor
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    
        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     * @return DatosProveedor
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
     * Set proveedor
     *
     * @param \FTP\AdministradorBundle\Entity\Proveedor $proveedor
     * @return DatosProveedor
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
}
