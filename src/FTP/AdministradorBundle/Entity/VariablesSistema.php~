<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VariablesSistema
 *
 * @ORM\Table(name="variables_sistema")
 * @ORM\Entity
 */
class VariablesSistema
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
     * @ORM\Column(name="minimo_pedido", type="integer", nullable=false)
     */
    private $minimoPedido;



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
     * Set minimoPedido
     *
     * @param integer $minimoPedido
     * @return VariablesSistema
     */
    public function setMinimoPedido($minimoPedido)
    {
        $this->minimoPedido = $minimoPedido;

        return $this;
    }

    /**
     * Get minimoPedido
     *
     * @return integer 
     */
    public function getMinimoPedido()
    {
        return $this->minimoPedido;
    }
}
