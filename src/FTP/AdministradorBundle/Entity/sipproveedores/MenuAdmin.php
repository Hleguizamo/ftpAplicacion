<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuAdmin
 *
 * @ORM\Table(name="menu_admin")
 * @ORM\Entity
 */
class MenuAdmin
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


}
