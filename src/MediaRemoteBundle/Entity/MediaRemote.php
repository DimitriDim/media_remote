<?php

namespace MediaRemoteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MediaRemote
 *
 * @ORM\Table(name="media_remote", uniqueConstraints={@ORM\UniqueConstraint(name="remoteId", columns={"remote_id", "media_id"})}, indexes={@ORM\Index(name="remoteId_2", columns={"remote_id"}), @ORM\Index(name="mediaId", columns={"media_id"})})
 * @ORM\Entity(repositoryClass="MediaRemoteBundle\Repository\MediaRemoteRepository")
 */
class MediaRemote
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="media_remote_active", type="boolean", nullable=false)
     */
    private $mediaRemoteActive;

    /**
     * @var integer
     *
     * @ORM\Column(name="media_remote_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $mediaRemoteId;

    /**
     * @var \MediaRemoteBundle\Entity\Remote
     *
     * @ORM\ManyToOne(targetEntity="MediaRemoteBundle\Entity\Remote")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="remote_id", referencedColumnName="remote_id")
     * })
     */
    private $remote;

    /**
     * @var \MediaRemoteBundle\Entity\Media
     *
     * @ORM\ManyToOne(targetEntity="MediaRemoteBundle\Entity\Media")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="media_id", referencedColumnName="media_id")
     * })
     */
    private $media;



    /**
     * Set mediaRemoteActive
     *
     * @param boolean $mediaRemoteActive
     *
     * @return MediaRemote
     */
    public function setMediaRemoteActive($mediaRemoteActive)
    {
        $this->mediaRemoteActive = $mediaRemoteActive;

        return $this;
    }

    /**
     * Get mediaRemoteActive
     *
     * @return boolean
     */
    public function getMediaRemoteActive()
    {
        return $this->mediaRemoteActive;
    }

    /**
     * Get mediaRemoteId
     *
     * @return integer
     */
    public function getMediaRemoteId()
    {
        return $this->mediaRemoteId;
    }

    /**
     * Set remote
     *
     * @param \MediaRemoteBundle\Entity\Remote $remote
     *
     * @return MediaRemote
     */
    public function setRemote(\MediaRemoteBundle\Entity\Remote $remote = null)
    {
        $this->remote = $remote;

        return $this;
    }

    /**
     * Get remote
     *
     * @return \MediaRemoteBundle\Entity\Remote
     */
    public function getRemote()
    {
        return $this->remote;
    }

    /**
     * Set media
     *
     * @param \MediaRemoteBundle\Entity\Media $media
     *
     * @return MediaRemote
     */
    public function setMedia(\MediaRemoteBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \MediaRemoteBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
}
