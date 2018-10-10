<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PermisosAdmin
 *
 * @ORM\Table(name="permisos_admin", indexes={@ORM\Index(name="id_permiso_idx", columns={"id_permiso"})})
 * @ORM\Entity
 */
class PermisosAdmin
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
     * @var boolean
     *
     * @ORM\Column(name="tipo_permiso", type="boolean", nullable=false)
     */
    private $tipoPermiso;


}
