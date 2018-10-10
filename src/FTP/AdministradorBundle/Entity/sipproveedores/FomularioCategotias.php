<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FomularioCategotias
 *
 * @ORM\Table(name="fomulario_categotias")
 * @ORM\Entity
 */
class FomularioCategotias
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
     * @ORM\Column(name="categoria", type="string", length=40, nullable=false)
     */
    private $categoria;


}
