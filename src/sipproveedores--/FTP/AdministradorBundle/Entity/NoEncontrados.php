<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NoEncontrados
 */
class NoEncontrados
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $codigoAsociado;

    /**
     * @var integer
     */
    private $centro;

    /**
     * @var string
     */
    private $nombreAsociado;

    /**
     * @var string
     */
    private $codigo;

    /**
     * @var string
     */
    private $producto;

    /**
     * @var string
     */
    private $cantidad;

    /**
     * @var string
     */
    private $codigoBarras;

    /**
     * @var \DateTime
     */
    private $tiempo;


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
     * Set codigoAsociado
     *
     * @param integer $codigoAsociado
     * @return NoEncontrados
     */
    public function setCodigoAsociado($codigoAsociado)
    {
        $this->codigoAsociado = $codigoAsociado;
    
        return $this;
    }

    /**
     * Get codigoAsociado
     *
     * @return integer 
     */
    public function getCodigoAsociado()
    {
        return $this->codigoAsociado;
    }

    /**
     * Set centro
     *
     * @param integer $centro
     * @return NoEncontrados
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
     * Set nombreAsociado
     *
     * @param string $nombreAsociado
     * @return NoEncontrados
     */
    public function setNombreAsociado($nombreAsociado)
    {
        $this->nombreAsociado = $nombreAsociado;
    
        return $this;
    }

    /**
     * Get nombreAsociado
     *
     * @return string 
     */
    public function getNombreAsociado()
    {
        return $this->nombreAsociado;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return NoEncontrados
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    
        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set producto
     *
     * @param string $producto
     * @return NoEncontrados
     */
    public function setProducto($producto)
    {
        $this->producto = $producto;
    
        return $this;
    }

    /**
     * Get producto
     *
     * @return string 
     */
    public function getProducto()
    {
        return $this->producto;
    }

    /**
     * Set cantidad
     *
     * @param string $cantidad
     * @return NoEncontrados
     */
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;
    
        return $this;
    }

    /**
     * Get cantidad
     *
     * @return string 
     */
    public function getCantidad()
    {
        return $this->cantidad;
    }

    /**
     * Set codigoBarras
     *
     * @param string $codigoBarras
     * @return NoEncontrados
     */
    public function setCodigoBarras($codigoBarras)
    {
        $this->codigoBarras = $codigoBarras;
    
        return $this;
    }

    /**
     * Get codigoBarras
     *
     * @return string 
     */
    public function getCodigoBarras()
    {
        return $this->codigoBarras;
    }

    /**
     * Set tiempo
     *
     * @param \DateTime $tiempo
     * @return NoEncontrados
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
}
