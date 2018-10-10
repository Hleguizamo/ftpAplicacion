<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bonificacion
 *
 * @ORM\Table(name="bonificacion", indexes={@ORM\Index(name="codigo", columns={"codigo"})})
 * @ORM\Entity
 */
class Bonificacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_bonificacion", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idBonificacion;

    /**
     * @var integer
     *
     * @ORM\Column(name="centro", type="integer", nullable=true)
     */
    private $centro;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_copi_proveedor", type="string", length=3, nullable=false)
     */
    private $codCopiProveedor;

    /**
     * @var string
     *
     * @ORM\Column(name="proveedor", type="string", length=80, nullable=false)
     */
    private $proveedor;

    /**
     * @var integer
     *
     * @ORM\Column(name="codigo", type="integer", nullable=false)
     */
    private $codigo;

    /**
     * @var string
     *
     * @ORM\Column(name="definicion", type="string", length=80, nullable=false)
     */
    private $definicion;

    /**
     * @var integer
     *
     * @ORM\Column(name="uni_sol", type="integer", nullable=true)
     */
    private $uniSol;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad1", type="integer", nullable=true)
     */
    private $cantidad1;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_obsequio", type="string", length=30, nullable=false)
     */
    private $codigoObsequio;

    /**
     * @var string
     *
     * @ORM\Column(name="obsequio", type="string", length=80, nullable=false)
     */
    private $obsequio;

    /**
     * @var integer
     *
     * @ORM\Column(name="cantidad2", type="integer", nullable=true)
     */
    private $cantidad2;

    /**
     * @var integer
     *
     * @ORM\Column(name="precio", type="integer", nullable=true)
     */
    private $precio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=true)
     */
    private $fecha = '0000-00-00';



    /**
     * Get idBonificacion
     *
     * @return integer 
     */
    public function getIdBonificacion()
    {
        return $this->idBonificacion;
    }

    /**
     * Set centro
     *
     * @param integer $centro
     * @return Bonificacion
     */
    public function setCentro($centro)
    {
        $this->centro = $centro;
    
        return $this;
    }

    /**
     * Get centro
     *
     * @return integer 
     */
    public function getCentro()
    {
        return $this->centro;
    }

    /**
     * Set codCopiProveedor
     *
     * @param string $codCopiProveedor
     * @return Bonificacion
     */
    public function setCodCopiProveedor($codCopiProveedor)
    {
        $this->codCopiProveedor = $codCopiProveedor;
    
        return $this;
    }

    /**
     * Get codCopiProveedor
     *
     * @return string 
     */
    public function getCodCopiProveedor()
    {
        return $this->codCopiProveedor;
    }

    /**
     * Set proveedor
     *
     * @param string $proveedor
     * @return Bonificacion
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
    
        return $this;
    }

    /**
     * Get proveedor
     *
     * @return string 
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set codigo
     *
     * @param integer $codigo
     * @return Bonificacion
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return integer 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set definicion
     *
     * @param string $definicion
     * @return Bonificacion
     */
    public function setDefinicion($definicion)
    {
        $this->definicion = $definicion;
    
        return $this;
    }

    /**
     * Get definicion
     *
     * @return string 
     */
    public function getDefinicion()
    {
        return $this->definicion;
    }

    /**
     * Set uniSol
     *
     * @param integer $uniSol
     * @return Bonificacion
     */
    public function setUniSol($uniSol)
    {
        $this->uniSol = $uniSol;
    
        return $this;
    }

    /**
     * Get uniSol
     *
     * @return integer 
     */
    public function getUniSol()
    {
        return $this->uniSol;
    }

    /**
     * Set cantidad1
     *
     * @param integer $cantidad1
     * @return Bonificacion
     */
    public function setCantidad1($cantidad1)
    {
        $this->cantidad1 = $cantidad1;
    
        return $this;
    }

    /**
     * Get cantidad1
     *
     * @return integer 
     */
    public function getCantidad1()
    {
        return $this->cantidad1;
    }

    /**
     * Set codigoObsequio
     *
     * @param string $codigoObsequio
     * @return Bonificacion
     */
    public function setCodigoObsequio($codigoObsequio)
    {
        $this->codigoObsequio = $codigoObsequio;
    
        return $this;
    }

    /**
     * Get codigoObsequio
     *
     * @return string 
     */
    public function getCodigoObsequio()
    {
        return $this->codigoObsequio;
    }

    /**
     * Set obsequio
     *
     * @param string $obsequio
     * @return Bonificacion
     */
    public function setObsequio($obsequio)
    {
        $this->obsequio = $obsequio;
    
        return $this;
    }

    /**
     * Get obsequio
     *
     * @return string 
     */
    public function getObsequio()
    {
        return $this->obsequio;
    }

    /**
     * Set cantidad2
     *
     * @param integer $cantidad2
     * @return Bonificacion
     */
    public function setCantidad2($cantidad2)
    {
        $this->cantidad2 = $cantidad2;
    
        return $this;
    }

    /**
     * Get cantidad2
     *
     * @return integer 
     */
    public function getCantidad2()
    {
        return $this->cantidad2;
    }

    /**
     * Set precio
     *
     * @param integer $precio
     * @return Bonificacion
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    
        return $this;
    }

    /**
     * Get precio
     *
     * @return integer 
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Bonificacion
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
