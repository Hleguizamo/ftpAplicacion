<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogEstructuraArchivos
 *
 * @ORM\Table(name="log_estructura_archivos", indexes={@ORM\Index(name="log_archivo", columns={"log_archivo"}), @ORM\Index(name="log_admin", columns={"log_admin"})})
 * @ORM\Entity
 */
class LogEstructuraArchivos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @var string
     *
     * @ORM\Column(name="contenido", type="string", length=100, nullable=true)
     */
    private $contenido;

    /**
     * @var integer
     *
     * @ORM\Column(name="log_archivo", type="bigint", nullable=true)
     */
    private $logArchivo;

    /**
     * @var integer
     *
     * @ORM\Column(name="log_admin", type="bigint", nullable=true)
     */
    private $logAdmin;



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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return LogEstructuraArchivos
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return LogEstructuraArchivos
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    
        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    /**
     * Set logArchivo
     *
     * @param integer $logArchivo
     * @return LogEstructuraArchivos
     */
    public function setLogArchivo($logArchivo)
    {
        $this->logArchivo = $logArchivo;
    
        return $this;
    }

    /**
     * Get logArchivo
     *
     * @return integer 
     */
    public function getLogArchivo()
    {
        return $this->logArchivo;
    }

    /**
     * Set logAdmin
     *
     * @param integer $logAdmin
     * @return LogEstructuraArchivos
     */
    public function setLogAdmin($logAdmin)
    {
        $this->logAdmin = $logAdmin;
    
        return $this;
    }

    /**
     * Get logAdmin
     *
     * @return integer 
     */
    public function getLogAdmin()
    {
        return $this->logAdmin;
    }
}
