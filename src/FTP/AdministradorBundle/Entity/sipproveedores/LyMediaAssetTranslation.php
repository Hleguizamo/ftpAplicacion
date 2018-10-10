<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LyMediaAssetTranslation
 *
 * @ORM\Table(name="ly_media_asset_translation", indexes={@ORM\Index(name="IDX_E020F8A8BF396750", columns={"id"})})
 * @ORM\Entity
 */
class LyMediaAssetTranslation
{
    /**
     * @var string
     *
     * @ORM\Column(name="lang", type="string", length=2, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $lang = '';

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \LyMediaAsset
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="LyMediaAsset")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;


}
