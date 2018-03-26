<?php

namespace MediaRemoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MediaRemoteFile
 *
 * @ORM\Table(name="media_remote_file")
 * @ORM\Entity
 */
class MediaRemoteFile
{
    /**
     * @var string
     *
     * @ORM\Column(name="files", type="string", length=255, nullable=false)
     */
    private $files;

    /**
     * @var \MediaRemoteBundle\Entity\MediaRemote
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="MediaRemoteBundle\Entity\MediaRemote")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_remote_file_id", referencedColumnName="media_remote_id")
     * })
     */
    private $mediaRemoteFile;



    /**
     * Set files
     *
     * @param string $files
     *
     * @return MediaRemoteFile
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Get files
     *
     * @return string
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set mediaRemoteFile
     *
     * @param \MediaRemoteBundle\Entity\MediaRemote $mediaRemoteFile
     *
     * @return MediaRemoteFile
     */
    public function setMediaRemoteFile(\MediaRemoteBundle\Entity\MediaRemote $mediaRemoteFile)
    {
        $this->mediaRemoteFile = $mediaRemoteFile;

        return $this;
    }

    /**
     * Get mediaRemoteFile
     *
     * @return \MediaRemoteBundle\Entity\MediaRemote
     */
    public function getMediaRemoteFile()
    {
        return $this->mediaRemoteFile;
    }
}
