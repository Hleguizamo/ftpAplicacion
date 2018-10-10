<?php

namespace FTP\AdministradorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LyMediaAsset
 */
class LyMediaAsset
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $copyright;

    /**
     * @var string
     */
    private $type;

    /**
     * @var integer
     */
    private $filesize;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \FTP\AdministradorBundle\Entity\LyMediaFolder
     */
    private $folder;


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
     * Set filename
     *
     * @param string $filename
     * @return LyMediaAsset
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return LyMediaAsset
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set copyright
     *
     * @param string $copyright
     * @return LyMediaAsset
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    
        return $this;
    }

    /**
     * Get copyright
     *
     * @return string 
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return LyMediaAsset
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set filesize
     *
     * @param integer $filesize
     * @return LyMediaAsset
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;
    
        return $this;
    }

    /**
     * Get filesize
     *
     * @return integer 
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return LyMediaAsset
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return LyMediaAsset
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set folder
     *
     * @param \FTP\AdministradorBundle\Entity\LyMediaFolder $folder
     * @return LyMediaAsset
     */
    public function setFolder(\FTP\AdministradorBundle\Entity\LyMediaFolder $folder = null)
    {
        $this->folder = $folder;
    
        return $this;
    }

    /**
     * Get folder
     *
     * @return \FTP\AdministradorBundle\Entity\LyMediaFolder 
     */
    public function getFolder()
    {
        return $this->folder;
    }
}
