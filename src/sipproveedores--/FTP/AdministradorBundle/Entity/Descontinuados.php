<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Descontinuados
 */
class Descontinuados
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $codCopiProveedor;

    /**
     * @var string
     */
    private $proveedor;

    /**
     * @var string
     */
    private $codigoProducto;

    /**
     * @var string
     */
    private $producto;

    /**
     * @var \DateTime
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
     * Set codCopiProveedor
     *
     * @param string $codCopiProveedor
     * @return Descontinuados
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
     * @return Descontinuados
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
     * Set codigoProducto
     *
     * @param string $codigoProducto
     * @return Descontinuados
     */
    public function setCodigoProducto($codigoProducto)
    {
        $this->codigoProducto = $codigoProducto;
    
        return $this;
    }

    /**
     * Get codigoProducto
     *
     * @return string 
     */
    public function getCodigoProducto()
    {
        return $this->codigoProducto;
    }

    /**
     * Set producto
     *
     * @param string $producto
     * @return Descontinuados
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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Descontinuados
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
