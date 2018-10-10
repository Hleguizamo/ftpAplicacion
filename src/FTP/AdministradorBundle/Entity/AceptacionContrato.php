<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AceptacionContrato
 *
 * @ORM\Table(name="aceptacion_contrato", indexes={@ORM\Index(name="codigo_drogeria_idx", columns={"codigo_drogeria"})})
 * @ORM\Entity
 */
class AceptacionContrato
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
     * @var integer
     *
     * @ORM\Column(name="codigo_drogeria", type="integer", nullable=false)
     */
    private $codigoDrogeria;

    /**
     * @var string
     *
     * @ORM\Column(name="nit_asociado", type="string", length=20, nullable=false)
     */
    private $nitAsociado;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=true)
     */
    private $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="clave", type="string", length=100, nullable=false)
     */
    private $clave;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="tiempo", type="datetime", nullable=false)
     */
    private $tiempo = '0000-00-00 00:00:00';

    /**
     * @var boolean
     *
     * @ORM\Column(name="pruebas", type="boolean", nullable=true)
     */
    private $pruebas;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ultimo_acceso", type="date", nullable=false)
     */
    private $ultimoAcceso = '0000-00-00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cambio_clave", type="date", nullable=false)
     */
    private $cambioClave = '0000-00-00';

    /**
     * @var string
     *
     * @ORM\Column(name="clave1", type="string", length=100, nullable=false)
     */
    private $clave1;

    /**
     * @var string
     *
     * @ORM\Column(name="clave2", type="string", length=100, nullable=false)
     */
    private $clave2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ultima_clave", type="boolean", nullable=true)
     */
    private $ultimaClave;



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
     * Set codigoDrogeria
     *
     * @param integer $codigoDrogeria
     * @return AceptacionContrato
     */
    public function setCodigoDrogeria($codigoDrogeria)
    {
        $this->codigoDrogeria = $codigoDrogeria;

        return $this;
    }

    /**
     * Get codigoDrogeria
     *
     * @return integer 
     */
    public function getCodigoDrogeria()
    {
        return $this->codigoDrogeria;
    }

    /**
     * Set nitAsociado
     *
     * @param string $nitAsociado
     * @return AceptacionContrato
     */
    public function setNitAsociado($nitAsociado)
    {
        $this->nitAsociado = $nitAsociado;

        return $this;
    }

    /**
     * Get nitAsociado
     *
     * @return string 
     */
    public function getNitAsociado()
    {
        return $this->nitAsociado;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return AceptacionContrato
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean 
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set clave
     *
     * @param string $clave
     * @return AceptacionContrato
     */
    public function setClave($clave)
    {
        $this->clave = $clave;

        return $this;
    }

    /**
     * Get clave
     *
     * @return string 
     */
    public function getClave()
    {
        return $this->clave;
    }

    /**
     * Set tiempo
     *
     * @param \DateTime $tiempo
     * @return AceptacionContrato
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    /**
     * Get tiempo
     *
     * @return \DateTime 
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set pruebas
     *
     * @param boolean $pruebas
     * @return AceptacionContrato
     */
    public function setPruebas($pruebas)
    {
        $this->pruebas = $pruebas;

        return $this;
    }

    /**
     * Get pruebas
     *
     * @return boolean 
     */
    public function getPruebas()
    {
        return $this->pruebas;
    }

    /**
     * Set ultimoAcceso
     *
     * @param \DateTime $ultimoAcceso
     * @return AceptacionContrato
     */
    public function setUltimoAcceso($ultimoAcceso)
    {
        $this->ultimoAcceso = $ultimoAcceso;

        return $this;
    }

    /**
     * Get ultimoAcceso
     *
     * @return \DateTime 
     */
    public function getUltimoAcceso()
    {
        return $this->ultimoAcceso;
    }

    /**
     * Set cambioClave
     *
     * @param \DateTime $cambioClave
     * @return AceptacionContrato
     */
    public function setCambioClave($cambioClave)
    {
        $this->cambioClave = $cambioClave;

        return $this;
    }

    /**
     * Get cambioClave
     *
     * @return \DateTime 
     */
    public function getCambioClave()
    {
        return $this->cambioClave;
    }

    /**
     * Set clave1
     *
     * @param string $clave1
     * @return AceptacionContrato
     */
    public function setClave1($clave1)
    {
        $this->clave1 = $clave1;

        return $this;
    }

    /**
     * Get clave1
     *
     * @return string 
     */
    public function getClave1()
    {
        return $this->clave1;
    }

    /**
     * Set clave2
     *
     * @param string $clave2
     * @return AceptacionContrato
     */
    public function setClave2($clave2)
    {
        $this->clave2 = $clave2;

        return $this;
    }

    /**
     * Get clave2
     *
     * @return string 
     */
    public function getClave2()
    {
        return $this->clave2;
    }

    /**
     * Set ultimaClave
     *
     * @param boolean $ultimaClave
     * @return AceptacionContrato
     */
    public function setUltimaClave($ultimaClave)
    {
        $this->ultimaClave = $ultimaClave;

        return $this;
    }

    /**
     * Get ultimaClave
     *
     * @return boolean 
     */
    public function getUltimaClave()
    {
        return $this->ultimaClave;
    }
}
