<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Administrador
 *
 * @ORM\Table(name="administrador", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id"})})
 * @ORM\Entity
 */
class Administrador implements UserInterface
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
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="usuario", type="string", length=30, nullable=false)
     */
    private $usuario;

    /**
     * @var string
     *
     * @ORM\Column(name="clave", type="string", length=100, nullable=false)
     */
    private $clave;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="seguimiento", type="boolean", nullable=true)
     */
    private $seguimiento;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creado", type="datetime", nullable=false)
     */
    private $fechaCreado;

    /**
     * @var integer
     *
     * @ORM\Column(name="modificado_id", type="integer", nullable=true)
     */
    private $modificadoId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ultimo_ingreso", type="datetime", nullable=true)
     */
    private $ultimoIngreso;

    /**
     * @var string
     *
     * @ORM\Column(name="ultima_ip", type="string", length=30, nullable=true)
     */
    private $ultimaIp;

    /**
     * @var boolean
     *
     * @ORM\Column(name="estado", type="boolean", nullable=false)
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
     * Set nombre
     *
     * @param string $nombre
     * @return Administrador
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
     * Set usuario
     *
     * @param string $usuario
     * @return Administrador
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
     * @return Administrador
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
     * Set email
     *
     * @param string $email
     * @return Administrador
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
     * Set seguimiento
     *
     * @param boolean $seguimiento
     * @return Administrador
     */
    public function setSeguimiento($seguimiento)
    {
        $this->seguimiento = $seguimiento;

        return $this;
    }

    /**
     * Get seguimiento
     *
     * @return boolean 
     */
    public function getSeguimiento()
    {
        return $this->seguimiento;
    }

    /**
     * Set fechaCreado
     *
     * @param \DateTime $fechaCreado
     * @return Administrador
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
     * Set modificadoId
     *
     * @param integer $modificadoId
     * @return Administrador
     */
    public function setModificadoId($modificadoId)
    {
        $this->modificadoId = $modificadoId;

        return $this;
    }

    /**
     * Get modificadoId
     *
     * @return integer 
     */
    public function getModificadoId()
    {
        return $this->modificadoId;
    }

    /**
     * Set ultimoIngreso
     *
     * @param \DateTime $ultimoIngreso
     * @return Administrador
     */
    public function setUltimoIngreso($ultimoIngreso)
    {
        $this->ultimoIngreso = $ultimoIngreso;

        return $this;
    }

    /**
     * Get ultimoIngreso
     *
     * @return \DateTime 
     */
    public function getUltimoIngreso()
    {
        return $this->ultimoIngreso;
    }

    /**
     * Set ultimaIp
     *
     * @param string $ultimaIp
     * @return Administrador
     */
    public function setUltimaIp($ultimaIp)
    {
        $this->ultimaIp = $ultimaIp;

        return $this;
    }

    /**
     * Get ultimaIp
     *
     * @return string 
     */
    public function getUltimaIp()
    {
        return $this->ultimaIp;
    }

    /**
     * Set estado
     *
     * @param boolean $estado
     * @return Administrador
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

    
     public function eraseCredentials() {
      $this->password = null;
    }

    public function getPassword() {
        return $this->getClave();
    }

    public function getRoles() {
        return array('ROLE_ADMINISTRADOR');
    }

    public function getSalt() {
        
    }

    public function getUsername() {
        return $this->getUsuario();
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return true;
    }
}
