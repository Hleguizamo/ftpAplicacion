<?php

namespace FTP\AdministradorBundle\Entity\sipproveedores;

use Doctrine\ORM\Mapping as ORM;

/**
 * PedidosConsecutivos
 *
 * @ORM\Table(name="pedidos_consecutivos", indexes={@ORM\Index(name="id_pedido", columns={"id_pedido"}), @ORM\Index(name="id_descripcion_pedido", columns={"id_descripcion_pedido"})})
 * @ORM\Entity
 */
class PedidosConsecutivos
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
     * @ORM\Column(name="id_pedido", type="integer", nullable=false)
     */
    private $idPedido;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_descripcion_pedido", type="integer", nullable=true)
     */
    private $idDescripcionPedido;

    /**
     * @var string
     *
     * @ORM\Column(name="consecutivo", type="string", length=20, nullable=false)
     */
    private $consecutivo;


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
     * Set idPedido
     *
     * @param integer $idPedido
     * @return PedidosConsecutivos
     */
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;

        return $this;
    }

    /**
     * Get idPedido
     *
     * @return integer 
     */
    public function getIdPedido()
    {
        return $this->idPedido;
    }


    /**
     * Set idDescripcionPedido
     *
     * @param integer $idDescripcionPedido
     * @return PedidosConsecutivos
     */
    public function setIdDescripcionPedido($idDescripcionPedido)
    {
        $this->idDescripcionPedido = $idDescripcionPedido;

        return $this;
    }

    /**
     * Get idDescripcionPedido
     *
     * @return integer 
     */
    public function getIdDescripcionPedido()
    {
        return $this->idDescripcionPedido;
    }


    /**
     * Set consecutivo
     *
     * @param string $consecutivo
     * @return PedidosConsecutivos
     */
    public function setConsecutivo($consecutivo)
    {
        $this->consecutivo = $consecutivo;

        return $this;
    }

    /**
     * Get consecutivo
     *
     * @return string 
     */
    public function getConsecutivo()
    {
        return $this->consecutivo;
    }
}
