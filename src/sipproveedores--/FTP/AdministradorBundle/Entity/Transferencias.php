<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transferencias
 */
class Transferencias
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nitProveedor;

    /**
     * @var integer
     */
    private $numTransferencia;

    /**
     * @var \DateTime
     */
    private $fechaTrans;

    /**
     * @var string
     */
    private $numFactura;

    /**
     * @var \DateTime
     */
    private $fechaFactura;

    /**
     * @var integer
     */
    private $centro;

    /**
     * @var integer
     */
    private $codDrogueria;

    /**
     * @var string
     */
    private $drogueria;

    /**
     * @var string
     */
    private $direccion;

    /**
     * @var string
     */
    private $departamento;

    /**
     * @var string
     */
    private $ciudad;

    /**
     * @var string
     */
    private $posicion;

    /**
     * @var string
     */
    private $proveedor;

    /**
     * @var string
     */
    private $division;

    /**
     * @var string
     */
    private $codMaterial;

    /**
     * @var string
     */
    private $material;

    /**
     * @var string
     */
    private $lote;

    /**
     * @var integer
     */
    private $cntTrans;

    /**
     * @var float
     */
    private $valTrans;

    /**
     * @var float
     */
    private $cantFact;

    /**
     * @var float
     */
    private $valFac;

    /**
     * @var string
     */
    private $transf;

    /**
     * @var string
     */
    private $referencia;

    /**
     * @var string
     */
    private $estado;


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
     * Set nitProveedor
     *
     * @param string $nitProveedor
     * @return Transferencias
     */
    public function setNitProveedor($nitProveedor)
    {
        $this->nitProveedor = $nitProveedor;
    
        return $this;
    }

    /**
     * Get nitProveedor
     *
     * @return string 
     */
    public function getNitProveedor()
    {
        return $this->nitProveedor;
    }

    /**
     * Set numTransferencia
     *
     * @param integer $numTransferencia
     * @return Transferencias
     */
    public function setNumTransferencia($numTransferencia)
    {
        $this->numTransferencia = $numTransferencia;
    
        return $this;
    }

    /**
     * Get numTransferencia
     *
     * @return integer 
     */
    public function getNumTransferencia()
    {
        return $this->numTransferencia;
    }

    /**
     * Set fechaTrans
     *
     * @param \DateTime $fechaTrans
     * @return Transferencias
     */
    public function setFechaTrans($fechaTrans)
    {
        $this->fechaTrans = $fechaTrans;
    
        return $this;
    }

    /**
     * Get fechaTrans
     *
     * @return \DateTime 
     */
    public function getFechaTrans()
    {
        return $this->fechaTrans;
    }

    /**
     * Set numFactura
     *
     * @param string $numFactura
     * @return Transferencias
     */
    public function setNumFactura($numFactura)
    {
        $this->numFactura = $numFactura;
    
        return $this;
    }

    /**
     * Get numFactura
     *
     * @return string 
     */
    public function getNumFactura()
    {
        return $this->numFactura;
    }

    /**
     * Set fechaFactura
     *
     * @param \DateTime $fechaFactura
     * @return Transferencias
     */
    public function setFechaFactura($fechaFactura)
    {
        $this->fechaFactura = $fechaFactura;
    
        return $this;
    }

    /**
     * Get fechaFactura
     *
     * @return \DateTime 
     */
    public function getFechaFactura()
    {
        return $this->fechaFactura;
    }

    /**
     * Set centro
     *
     * @param integer $centro
     * @return Transferencias
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
     * Set codDrogueria
     *
     * @param integer $codDrogueria
     * @return Transferencias
     */
    public function setCodDrogueria($codDrogueria)
    {
        $this->codDrogueria = $codDrogueria;
    
        return $this;
    }

    /**
     * Get codDrogueria
     *
     * @return integer 
     */
    public function getCodDrogueria()
    {
        return $this->codDrogueria;
    }

    /**
     * Set drogueria
     *
     * @param string $drogueria
     * @return Transferencias
     */
    public function setDrogueria($drogueria)
    {
        $this->drogueria = $drogueria;
    
        return $this;
    }

    /**
     * Get drogueria
     *
     * @return string 
     */
    public function getDrogueria()
    {
        return $this->drogueria;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Transferencias
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set departamento
     *
     * @param string $departamento
     * @return Transferencias
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    
        return $this;
    }

    /**
     * Get departamento
     *
     * @return string 
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     * @return Transferencias
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;
    
        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string 
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set posicion
     *
     * @param string $posicion
     * @return Transferencias
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;
    
        return $this;
    }

    /**
     * Get posicion
     *
     * @return string 
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set proveedor
     *
     * @param string $proveedor
     * @return Transferencias
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
     * Set division
     *
     * @param string $division
     * @return Transferencias
     */
    public function setDivision($division)
    {
        $this->division = $division;
    
        return $this;
    }

    /**
     * Get division
     *
     * @return string 
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Set codMaterial
     *
     * @param string $codMaterial
     * @return Transferencias
     */
    public function setCodMaterial($codMaterial)
    {
        $this->codMaterial = $codMaterial;
    
        return $this;
    }

    /**
     * Get codMaterial
     *
     * @return string 
     */
    public function getCodMaterial()
    {
        return $this->codMaterial;
    }

    /**
     * Set material
     *
     * @param string $material
     * @return Transferencias
     */
    public function setMaterial($material)
    {
        $this->material = $material;
    
        return $this;
    }

    /**
     * Get material
     *
     * @return string 
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * Set lote
     *
     * @param string $lote
     * @return Transferencias
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
    
        return $this;
    }

    /**
     * Get lote
     *
     * @return string 
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set cntTrans
     *
     * @param integer $cntTrans
     * @return Transferencias
     */
    public function setCntTrans($cntTrans)
    {
        $this->cntTrans = $cntTrans;
    
        return $this;
    }

    /**
     * Get cntTrans
     *
     * @return integer 
     */
    public function getCntTrans()
    {
        return $this->cntTrans;
    }

    /**
     * Set valTrans
     *
     * @param float $valTrans
     * @return Transferencias
     */
    public function setValTrans($valTrans)
    {
        $this->valTrans = $valTrans;
    
        return $this;
    }

    /**
     * Get valTrans
     *
     * @return float 
     */
    public function getValTrans()
    {
        return $this->valTrans;
    }

    /**
     * Set cantFact
     *
     * @param float $cantFact
     * @return Transferencias
     */
    public function setCantFact($cantFact)
    {
        $this->cantFact = $cantFact;
    
        return $this;
    }

    /**
     * Get cantFact
     *
     * @return float 
     */
    public function getCantFact()
    {
        return $this->cantFact;
    }

    /**
     * Set valFac
     *
     * @param float $valFac
     * @return Transferencias
     */
    public function setValFac($valFac)
    {
        $this->valFac = $valFac;
    
        return $this;
    }

    /**
     * Get valFac
     *
     * @return float 
     */
    public function getValFac()
    {
        return $this->valFac;
    }

    /**
     * Set transf
     *
     * @param string $transf
     * @return Transferencias
     */
    public function setTransf($transf)
    {
        $this->transf = $transf;
    
        return $this;
    }

    /**
     * Get transf
     *
     * @return string 
     */
    public function getTransf()
    {
        return $this->transf;
    }

    /**
     * Set referencia
     *
     * @param string $referencia
     * @return Transferencias
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
    
        return $this;
    }

    /**
     * Get referencia
     *
     * @return string 
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Transferencias
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    
        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado()
    {
        return $this->estado;
    }
}
