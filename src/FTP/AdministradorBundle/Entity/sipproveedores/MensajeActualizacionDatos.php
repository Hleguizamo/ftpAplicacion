<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MensajeActualizacionDatos
 *
 * @ORM\Table(name="mensaje_actualizacion_datos")
 * @ORM\Entity
 */
class MensajeActualizacionDatos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="dias_actualizar_proveedor", type="string", length=300, nullable=false)
     */
    private $diasActualizarProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="dias_bloqueo_proveedor", type="string", length=300, nullable=false)
     */
    private $diasBloqueoProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="dias_actualizar_transferencista", type="string", length=300, nullable=false)
     */
    private $diasActualizarTransferencista;

    /**
     * @var string
     *
     * @ORM\Column(name="dias_bloqeo_transferencista", type="string", length=300, nullable=false)
     */
    private $diasBloqeoTransferencista;

    /**
     * @var string
     *
     * @ORM\Column(name="bloqueado_proveedor", type="string", length=300, nullable=false)
     */
    private $bloqueadoProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="bloqueado_transferencista", type="string", length=300, nullable=false)
     */
    private $bloqueadoTransferencista;

    /**
     * @var string
     *
     * @ORM\Column(name="dias_actualizacion_bloqueado", type="string", length=300, nullable=true)
     */
    private $diasActualizacionBloqueado;

    /**
     * @var string
     *
     * @ORM\Column(name="levantamiento_bloqueo", type="string", length=500, nullable=true)
     */
    private $levantamientoBloqueo;


}
