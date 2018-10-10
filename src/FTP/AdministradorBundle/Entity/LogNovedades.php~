<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogNovedades
 *
 * @ORM\Table(name="log_novedades")
 * @ORM\Entity
 */
class LogNovedades
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
     * @var string
     *
     * @ORM\Column(name="novedad", type="string", length=450, nullable=true)
     */
    private $novedad;

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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime", nullable=true)
     */
    private $fecha;



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
     * Set novedad
     *
     * @param string $novedad
     * @return LogNovedades
     */
    public function setNovedad($novedad)
    {
        $this->novedad = $novedad;
    
        return $this;
    }

    /**
     * Get novedad
     *
     * @return string 
     */
    public function getNovedad()
    {
        return $this->novedad;
    }

    /**
     * Set logArchivo
     *
     * @param integer $logArchivo
     * @return LogNovedades
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
     * @return LogNovedades
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

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return LogNovedades
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
}
