<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedor
 *
 * @ORM\Table(name="proveedor", uniqueConstraints={@ORM\UniqueConstraint(name="usuario_UNIQUE", columns={"usuario"})})
 * @ORM\Entity
 */
class Proveedor
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
     * @ORM\Column(name="nombre", type="string", length=200, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_copidrogas", type="string", length=20, nullable=false)
     */
    private $codigoCopidrogas;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=100, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=100, nullable=true)
     */
    private $ciudad;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=50, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="representante", type="string", length=200, nullable=true)
     */
    private $representante;

    /**
     * @var string
     *
     * @ORM\Column(name="representante_telefono", type="string", length=50, nullable=true)
     */
    private $representanteTelefono;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=50, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=30, nullable=true)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="clave", type="string", length=100, nullable=true)
     */
    private $clave;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=50, nullable=true)
     */
    private $imagen;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable1", type="string", length=100, nullable=true)
     */
    private $responsable1;

    /**
     * @var string
     *
     * @ORM\Column(name="cargo1", type="string", length=100, nullable=true)
     */
    private $cargo1;

    /**
     * @var string
     *
     * @ORM\Column(name="email1", type="string", length=100, nullable=true)
     */
    private $email1;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable2", type="string", length=100, nullable=false)
     */
    private $responsable2;

    /**
     * @var string
     *
     * @ORM\Column(name="cargo2", type="string", length=100, nullable=false)
     */
    private $cargo2;

    /**
     * @var string
     *
     * @ORM\Column(name="email2", type="string", length=100, nullable=false)
     */
    private $email2;

    /**
     * @var string
     *
     * @ORM\Column(name="descuento_comercial", type="string", length=30, nullable=false)
     */
    private $descuentoComercial;

    /**
     * @var string
     *
     * @ORM\Column(name="descuento_finaciero", type="string", length=30, nullable=false)
     */
    private $descuentoFinaciero;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_material", type="string", length=20, nullable=false)
     */
    private $codigoMaterial;



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
     * Set nombre
     *
     * @param string $nombre
     * @return Proveedor
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set codigoCopidrogas
     *
     * @param string $codigoCopidrogas
     * @return Proveedor
     */
    public function setCodigoCopidrogas($codigoCopidrogas)
    {
        $this->codigoCopidrogas = $codigoCopidrogas;

        return $this;
    }

    /**
     * Get codigoCopidrogas
     *
     * @return string 
     */
    public function getCodigoCopidrogas()
    {
        return $this->codigoCopidrogas;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Proveedor
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
     * Set ciudad
     *
     * @param string $ciudad
     * @return Proveedor
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
     * Set telefono
     *
     * @param string $telefono
     * @return Proveedor
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set representante
     *
     * @param string $representante
     * @return Proveedor
     */
    public function setRepresentante($representante)
    {
        $this->representante = $representante;

        return $this;
    }

    /**
     * Get representante
     *
     * @return string 
     */
    public function getRepresentante()
    {
        return $this->representante;
    }

    /**
     * Set representanteTelefono
     *
     * @param string $representanteTelefono
     * @return Proveedor
     */
    public function setRepresentanteTelefono($representanteTelefono)
    {
        $this->representanteTelefono = $representanteTelefono;

        return $this;
    }

    /**
     * Get representanteTelefono
     *
     * @return string 
     */
    public function getRepresentanteTelefono()
    {
        return $this->representanteTelefono;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Proveedor
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Proveedor
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set usuario
     *
     * @param string $usuario
     * @return Proveedor
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return string 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set clave
     *
     * @param string $clave
     * @return Proveedor
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
     * Set imagen
     *
     * @param string $imagen
     * @return Proveedor
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string 
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set responsable1
     *
     * @param string $responsable1
     * @return Proveedor
     */
    public function setResponsable1($responsable1)
    {
        $this->responsable1 = $responsable1;

        return $this;
    }

    /**
     * Get responsable1
     *
     * @return string 
     */
    public function getResponsable1()
    {
        return $this->responsable1;
    }

    /**
     * Set cargo1
     *
     * @param string $cargo1
     * @return Proveedor
     */
    public function setCargo1($cargo1)
    {
        $this->cargo1 = $cargo1;

        return $this;
    }

    /**
     * Get cargo1
     *
     * @return string 
     */
    public function getCargo1()
    {
        return $this->cargo1;
    }

    /**
     * Set email1
     *
     * @param string $email1
     * @return Proveedor
     */
    public function setEmail1($email1)
    {
        $this->email1 = $email1;

        return $this;
    }

    /**
     * Get email1
     *
     * @return string 
     */
    public function getEmail1()
    {
        return $this->email1;
    }

    /**
     * Set responsable2
     *
     * @param string $responsable2
     * @return Proveedor
     */
    public function setResponsable2($responsable2)
    {
        $this->responsable2 = $responsable2;

        return $this;
    }

    /**
     * Get responsable2
     *
     * @return string 
     */
    public function getResponsable2()
    {
        return $this->responsable2;
    }

    /**
     * Set cargo2
     *
     * @param string $cargo2
     * @return Proveedor
     */
    public function setCargo2($cargo2)
    {
        $this->cargo2 = $cargo2;

        return $this;
    }

    /**
     * Get cargo2
     *
     * @return string 
     */
    public function getCargo2()
    {
        return $this->cargo2;
    }

    /**
     * Set email2
     *
     * @param string $email2
     * @return Proveedor
     */
    public function setEmail2($email2)
    {
        $this->email2 = $email2;

        return $this;
    }

    /**
     * Get email2
     *
     * @return string 
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * Set descuentoComercial
     *
     * @param string $descuentoComercial
     * @return Proveedor
     */
    public function setDescuentoComercial($descuentoComercial)
    {
        $this->descuentoComercial = $descuentoComercial;

        return $this;
    }

    /**
     * Get descuentoComercial
     *
     * @return string 
     */
    public function getDescuentoComercial()
    {
        return $this->descuentoComercial;
    }

    /**
     * Set descuentoFinaciero
     *
     * @param string $descuentoFinaciero
     * @return Proveedor
     */
    public function setDescuentoFinaciero($descuentoFinaciero)
    {
        $this->descuentoFinaciero = $descuentoFinaciero;

        return $this;
    }

    /**
     * Get descuentoFinaciero
     *
     * @return string 
     */
    public function getDescuentoFinaciero()
    {
        return $this->descuentoFinaciero;
    }

    /**
     * Set codigoMaterial
     *
     * @param string $codigoMaterial
     * @return Proveedor
     */
    public function setCodigoMaterial($codigoMaterial)
    {
        $this->codigoMaterial = $codigoMaterial;

        return $this;
    }

    /**
     * Get codigoMaterial
     *
     * @return string 
     */
    public function getCodigoMaterial()
    {
        return $this->codigoMaterial;
    }
}
