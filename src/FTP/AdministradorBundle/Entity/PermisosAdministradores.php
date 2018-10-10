<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PermisosAdministradores
 *
 * @ORM\Table(name="permisos_administradores")
 * @ORM\Entity
 */
class PermisosAdministradores
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
     * @var integer
     *
     * @ORM\Column(name="id_administrador", type="bigint", nullable=false)
     */
    private $idAdministrador;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_permiso", type="bigint", nullable=false)
     */
    private $idPermiso;

    /**
     * @var integer
     *
     * @ORM\Column(name="tipo_permiso", type="integer", nullable=false)
     */
    private $tipoPermiso;



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
     * Set idAdministrador
     *
     * @param integer $idAdministrador
     * @return PermisosAdministradores
     */
    public function setIdAdministrador($idAdministrador)
    {
        $this->idAdministrador = $idAdministrador;
    
        return $this;
    }

    /**
     * Get idAdministrador
     *
     * @return integer 
     */
    public function getIdAdministrador()
    {
        return $this->idAdministrador;
    }

    /**
     * Set idPermiso
     *
     * @param integer $idPermiso
     * @return PermisosAdministradores
     */
    public function setIdPermiso($idPermiso)
    {
        $this->idPermiso = $idPermiso;
    
        return $this;
    }

    /**
     * Get idPermiso
     *
     * @return integer 
     */
    public function getIdPermiso()
    {
        return $this->idPermiso;
    }

    /**
     * Set tipoPermiso
     *
     * @param integer $tipoPermiso
     * @return PermisosAdministradores
     */
    public function setTipoPermiso($tipoPermiso)
    {
        $this->tipoPermiso = $tipoPermiso;
    
        return $this;
    }

    /**
     * Get tipoPermiso
     *
     * @return integer 
     */
    public function getTipoPermiso()
    {
        return $this->tipoPermiso;
    }
}
