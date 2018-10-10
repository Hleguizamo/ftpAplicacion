<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FomularioSubCategotias
 */
class FomularioSubCategotias
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $subCategoria;


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
     * Set subCategoria
     *
     * @param string $subCategoria
     * @return FomularioSubCategotias
     */
    public function setSubCategoria($subCategoria)
    {
        $this->subCategoria = $subCategoria;
    
        return $this;
    }

    /**
     * Get subCategoria
     *
     * @return string 
     */
    public function getSubCategoria()
    {
        return $this->subCategoria;
    }
}
