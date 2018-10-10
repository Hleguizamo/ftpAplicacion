<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LyMediaAsset
 *
 * @ORM\Table(name="ly_media_asset", indexes={@ORM\Index(name="folder_id_idx", columns={"folder_id"})})
 * @ORM\Entity
 */
class LyMediaAsset
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
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="copyright", type="string", length=100, nullable=true)
     */
    private $copyright;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=80, nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="filesize", type="integer", nullable=true)
     */
    private $filesize;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var \LyMediaFolder
     *
     * @ORM\ManyToOne(targetEntity="LyMediaFolder")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="folder_id", referencedColumnName="id")
     * })
     */
    private $folder;


}
