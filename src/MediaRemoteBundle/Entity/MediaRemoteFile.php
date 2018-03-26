<?php

namespace MediaRemoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MediaRemoteFile
 *
 * @ORM\Table(name="media_remote_file", uniqueConstraints={@ORM\UniqueConstraint(name="media_remote_id_2", columns={"media_remote_id", "files"})}, indexes={@ORM\Index(name="media_remote_id", columns={"media_remote_id"})})
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
     * @var integer
     *
     * @ORM\Column(name="media_remote_file_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $mediaRemoteFileId;

    /**
     * @var \MediaRemoteBundle\Entity\MediaRemote
     *
     * @ORM\ManyToOne(targetEntity="MediaRemoteBundle\Entity\MediaRemote")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_remote_id", referencedColumnName="media_remote_id")
     * })
     */
    private $mediaRemote;



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
     * Get mediaRemoteFileId
     *
     * @return integer
     */
    public function getMediaRemoteFileId()
    {
        return $this->mediaRemoteFileId;
    }

    /**
     * Set mediaRemote
     *
     * @param \MediaRemoteBundle\Entity\MediaRemote $mediaRemote
     *
     * @return MediaRemoteFile
     */
    public function setMediaRemote(\MediaRemoteBundle\Entity\MediaRemote $mediaRemote = null)
    {
        $this->mediaRemote = $mediaRemote;

        return $this;
    }

    /**
     * Get mediaRemote
     *
     * @return \MediaRemoteBundle\Entity\MediaRemote
     */
    public function getMediaRemote()
    {
        return $this->mediaRemote;
    }
}
