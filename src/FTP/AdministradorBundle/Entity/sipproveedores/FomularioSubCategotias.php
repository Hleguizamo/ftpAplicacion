<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FomularioSubCategotias
 *
 * @ORM\Table(name="fomulario_sub_categotias")
 * @ORM\Entity
 */
class FomularioSubCategotias
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
     * @var string
     *
     * @ORM\Column(name="sub_categoria", type="string", length=30, nullable=false)
     */
    private $subCategoria;


}
