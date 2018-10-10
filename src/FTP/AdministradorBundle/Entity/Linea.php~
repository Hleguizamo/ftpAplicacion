<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Linea
 *
 * @ORM\Table(name="linea", indexes={@ORM\Index(name="creador", columns={"creador"}), @ORM\Index(name="actualizador", columns={"actualizador"})})
 * @ORM\Entity
 */
class Linea
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
     * @var integer
     *
     * @ORM\Column(name="line_id", type="integer", nullable=false)
     */
    private $lineId;

    /**
     * @var string
     *
     * @ORM\Column(name="linea", type="string", length=50, nullable=false)
     */
    private $linea;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creado", type="datetime", nullable=false)
     */
    private $fechaCreado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="frecha_actualizado", type="datetime", nullable=true)
     */
    private $frechaActualizado;

    /**
     * @var \Administradores
     *
     * @ORM\ManyToOne(targetEntity="Administradores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creador", referencedColumnName="id")
     * })
     */
   // private $creador;

    /**
     * @var \Administradores
     *
     * @ORM\ManyToOne(targetEntity="Administradores")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actualizador", referencedColumnName="id")
     * })
     */
    //private $actualizador;



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
     * Set lineId
     *
     * @param integer $lineId
     * @return Linea
     */
    public function setLineId($lineId)
    {
        $this->lineId = $lineId;

        return $this;
    }

    /**
     * Get lineId
     *
     * @return integer 
     */
    public function getLineId()
    {
        return $this->lineId;
    }

    /**
     * Set linea
     *
     * @param string $linea
     * @return Linea
     */
    public function setLinea($linea)
    {
        $this->linea = $linea;

        return $this;
    }

    /**
     * Get linea
     *
     * @return string 
     */
    public function getLinea()
    {
        return $this->linea;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return Linea
     */
    public function setFechaCreado($fechaCreado)
    {
        $this->fechaCreado = $fechaCreado;

        return $this;
    }

    /**
     * Get fechaCreado
     *
     * @return \DateTime 
     */
    public function getFechaCreado()
    {
        return $this->fechaCreado;
    }

    /**
     * Set frechaActualizado
     *
     * @param \DateTime $frechaActualizado
     * @return Linea
     */
    public function setFrechaActualizado($frechaActualizado)
    {
        $this->frechaActualizado = $frechaActualizado;

        return $this;
    }

    /**
     * Get frechaActualizado
     *
     * @return \DateTime 
     */
    public function getFrechaActualizado()
    {
        return $this->frechaActualizado;
    }

    /**
     * Set creador
     *
     * @param \FTP\AdministradorBundle\Entity\Administradores $creador
     * @return Linea
     */
   /* public function setCreador(\FTP\AdministradorBundle\Entity\Administradores $creador = null)
    {
        $this->creador = $creador;

        return $this;
    }
	*/

    /**
     * Get creador
     *
     * @return \FTP\AdministradorBundle\Entity\Administradores 
     */
   /* public function getCreador()
    {
        return $this->creador;
    }
	*/

    /**
     * Set actualizador
     *
     * @param \FTP\AdministradorBundle\Entity\Administradores $actualizador
     * @return Linea
     */
    /*public function setActualizador(\FTP\AdministradorBundle\Entity\Administradores $actualizador = null)
    {
        $this->actualizador = $actualizador;

        return $this;
    }
*/
    /**
     * Get actualizador
     *
     * @return \FTP\AdministradorBundle\Entity\Administradores 
     */
  /*  public function getActualizador()
    {
        return $this->actualizador;
    }
	*/
}
