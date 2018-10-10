<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuAdministradores
 *
 * @ORM\Table(name="menu_administradores")
 * @ORM\Entity
 */
class MenuAdministradores
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_permisos", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPermisos;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion_permiso", type="string", length=100, nullable=false)
     */
    private $descripcionPermiso;

    /**
     * @var string
     *
     * @ORM\Column(name="modulo_defecto", type="string", length=40, nullable=false)
     */
    private $moduloDefecto;

    /**
     * @var string
     *
     * @ORM\Column(name="accion_defecto", type="string", length=40, nullable=false)
     */
    private $accionDefecto;

    /**
     * @var boolean
     *
     * @ORM\Column(name="raiz", type="boolean", nullable=false)
     */
    private $raiz;

    /**
     * @var string
     *
     * @ORM\Column(name="accion_crear", type="string", length=250, nullable=false)
     */
    private $accionCrear;

    /**
     * @var string
     *
     * @ORM\Column(name="accion_editar", type="string", length=50, nullable=false)
     */
    private $accionEditar;

    /**
     * @var string
     *
     * @ORM\Column(name="accion_mostrar", type="string", length=50, nullable=false)
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
     * @return MenuAdministradores
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
     * @return MenuAdministradores
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
     * @return MenuAdministradores
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
     * @return MenuAdministradores
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
     * @return MenuAdministradores
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
     * @return MenuAdministradores
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
     * @return MenuAdministradores
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
