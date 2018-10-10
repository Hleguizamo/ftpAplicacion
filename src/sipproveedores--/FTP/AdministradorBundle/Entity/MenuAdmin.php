<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuAdmin
 */
class MenuAdmin
{
    /**
     * @var integer
     */
    private $idPermisos;

    /**
     * @var string
     */
    private $descripcionPermiso;

    /**
     * @var string
     */
    private $moduloDefecto;

    /**
     * @var string
     */
    private $accionDefecto;

    /**
     * @var boolean
     */
    private $raiz;

    /**
     * @var string
     */
    private $accionCrear;

    /**
     * @var string
     */
    private $accionEditar;

    /**
     * @var string
     */
    private $accionMostrar;


    /**
     * Get idPermisos
     *
     * @return integer 
     */
    public function getIdPermisos()
    {
        return $this->idPermisos;
    }

    /**
     * Set descripcionPermiso
     *
     * @param string $descripcionPermiso
     * @return MenuAdmin
     */
    public function setDescripcionPermiso($descripcionPermiso)
    {
        $this->descripcionPermiso = $descripcionPermiso;
    
        return $this;
    }

    /**
     * Get descripcionPermiso
     *
     * @return string 
     */
    public function getDescripcionPermiso()
    {
        return $this->descripcionPermiso;
    }

    /**
     * Set moduloDefecto
     *
     * @param string $moduloDefecto
     * @return MenuAdmin
     */
    public function setModuloDefecto($moduloDefecto)
    {
        $this->moduloDefecto = $moduloDefecto;
    
        return $this;
    }

    /**
     * Get moduloDefecto
     *
     * @return string 
     */
    public function getModuloDefecto()
    {
        return $this->moduloDefecto;
    }

    /**
     * Set accionDefecto
     *
     * @param string $accionDefecto
     * @return MenuAdmin
     */
    public function setAccionDefecto($accionDefecto)
    {
        $this->accionDefecto = $accionDefecto;
    
        return $this;
    }

    /**
     * Get accionDefecto
     *
     * @return string 
     */
    public function getAccionDefecto()
    {
        return $this->accionDefecto;
    }

    /**
     * Set raiz
     *
     * @param boolean $raiz
     * @return MenuAdmin
     */
    public function setRaiz($raiz)
    {
        $this->raiz = $raiz;
    
        return $this;
    }

    /**
     * Get raiz
     *
     * @return boolean 
     */
    public function getRaiz()
    {
        return $this->raiz;
    }

    /**
     * Set accionCrear
     *
     * @param string $accionCrear
     * @return MenuAdmin
     */
    public function setAccionCrear($accionCrear)
    {
        $this->accionCrear = $accionCrear;
    
        return $this;
    }

    /**
     * Get accionCrear
     *
     * @return string 
     */
    public function getAccionCrear()
    {
        return $this->accionCrear;
    }

    /**
     * Set accionEditar
     *
     * @param string $accionEditar
     * @return MenuAdmin
     */
    public function setAccionEditar($accionEditar)
    {
        $this->accionEditar = $accionEditar;
    
        return $this;
    }

    /**
     * Get accionEditar
     *
     * @return string 
     */
    public function getAccionEditar()
    {
        return $this->accionEditar;
    }

    /**
     * Set accionMostrar
     *
     * @param string $accionMostrar
     * @return MenuAdmin
     */
    public function setAccionMostrar($accionMostrar)
    {
        $this->accionMostrar = $accionMostrar;
    
        return $this;
    }

    /**
     * Get accionMostrar
     *
     * @return string 
     */
    public function getAccionMostrar()
    {
        return $this->accionMostrar;
    }
}
