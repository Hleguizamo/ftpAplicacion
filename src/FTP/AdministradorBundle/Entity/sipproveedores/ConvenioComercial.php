<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConvenioComercial
 *
 * @ORM\Table(name="convenio_comercial")
 * @ORM\Entity
 */
class ConvenioComercial
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
     * @ORM\Column(name="convenio", type="string", length=255, nullable=false)
     */
    private $convenio;


}
