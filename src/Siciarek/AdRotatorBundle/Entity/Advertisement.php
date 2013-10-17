<?php

namespace Siciarek\AdRotatorBundle\Entity;

use Siciarek\AdRotatorBundle\Utils\Time;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Advertisement
 */
class Advertisement
{

    /**
     * @ORM\PostPersist
     */
    public function postPersist() {
    }
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->prePersist();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $title = trim($this->getTitle());

        if (empty($title) === true) {
            $this->setTitle($this->getClient()->getName());
        }

        $option = $this->getOption();
        $start = $this->getStartsAt();
        $end = $this->getExpiresAt();

        $periods = array(
            'day' => 1,
            'week' => 7,
        );

        if ($option !== null) {
            if ($end === null) {
                $period = $option->getPeriod();
                $duration = $option->getDuration();
                $days = $periods[$period] * $duration;
                $this->setExpiresAt(Time::getDate($days, $start));
            }
            $this->setPrice($option->getPrice());
        }

        $this->upload();
    }

    public function __toString()
    {
        if ($this->getId() !== null) {
            return sprintf('%s - %s - %s', $this->getClient(), $this->getType(), $this->getOption());
        }
        return '';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setStartsAt(new \DateTime());
        $this->setPrice(0);
        $this->setEnabled(true);
        $this->setExclusive(false);
        $this->setEverlasting(false);
        $this->setFrequency(0);
        $this->setDisplayed(0);
        $this->setClicked(0);
        $this->advertisements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    protected $uploadRootDir;

    public function setUploadRootDir($root) {
        $this->uploadRootDir = $root . $this->getUploadDir();
    }

    protected function getUploadRootDir()
    {
        return $this->uploadRootDir;
    }

    public function getAbsolutePath()
    {
        return null === $this->getPath()
            ? null
            : $this->getUploadRootDir() . '/' . $this->getPath();
    }

    public function getWebPath()
    {
        return null === $this->getPath()
            ? null
            : $this->getUploadDir() . '/' . $this->getPath();
    }

    public function getUploadDir()
    {
        return 'uploads/sar/' . $this->getClient()->getId();
    }

    /**
     * UploadedFile
     */
    protected $uploaded_file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setUploadedFile(UploadedFile $uploaded_file = null)
    {
        $this->uploaded_file = $uploaded_file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploaded_file;
    }


// STEP THREE:

    public function upload($file_setter = 'setPath')
    {
        if (null === $this->getUploadedFile()) {
            return;
        }

        $ext = preg_replace("/^.*\.(\w+)$/", "$1", $this->getUploadedFile()->getClientOriginalName());

        do {
            $filename = sha1(uniqid(mt_rand(), true));
            $filename = $filename . "." . $ext;
            $fullname = $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $filename;
        } while (file_exists($fullname));

        $this->getUploadedFile()->move(
            $this->getUploadRootDir(),
            $fullname
        );

        $this->$file_setter($filename);

        $this->uploaded_file = null;
    }

//////////////////////////////////////////////////////////

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $price;

    /**
     * @var integer
     */
    private $displayed;

    /**
     * @var integer
     */
    private $clicked;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $leads_to;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var boolean
     */
    private $exclusive;

    /**
     * @var boolean
     */
    private $everlasting;

    /**
     * @var \DateTime
     */
    private $starts_at;

    /**
     * @var \DateTime
     */
    private $expires_at;

    /**
     * @var integer
     */
    private $frequency;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @var \Siciarek\AdRotatorBundle\Entity\AdvertisementPrice
     */
    private $option;

    /**
     * @var \Siciarek\AdRotatorBundle\Entity\AdvertisementType
     */
    private $type;

    /**
     * @var \Siciarek\AdRotatorBundle\Entity\Client
     */
    private $client;


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
     * Set title
     *
     * @param string $title
     * @return Advertisement
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Advertisement
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set displayed
     *
     * @param integer $displayed
     * @return Advertisement
     */
    public function setDisplayed($displayed)
    {
        $this->displayed = $displayed;

        return $this;
    }

    /**
     * Get displayed
     *
     * @return integer
     */
    public function getDisplayed()
    {
        return $this->displayed;
    }

    /**
     * Set clicked
     *
     * @param integer $clicked
     * @return Advertisement
     */
    public function setClicked($clicked)
    {
        $this->clicked = $clicked;

        return $this;
    }

    /**
     * Get clicked
     *
     * @return integer
     */
    public function getClicked()
    {
        return $this->clicked;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Advertisement
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Advertisement
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set leads_to
     *
     * @param string $leadsTo
     * @return Advertisement
     */
    public function setLeadsTo($leadsTo)
    {
        $this->leads_to = $leadsTo;

        return $this;
    }

    /**
     * Get leads_to
     *
     * @return string
     */
    public function getLeadsTo()
    {
        return $this->leads_to;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Advertisement
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set exclusive
     *
     * @param boolean $exclusive
     * @return Advertisement
     */
    public function setExclusive($exclusive)
    {
        $this->exclusive = $exclusive;

        return $this;
    }

    /**
     * Get exclusive
     *
     * @return boolean
     */
    public function getExclusive()
    {
        return $this->exclusive;
    }

    /**
     * Set everlasting
     *
     * @param boolean $everlasting
     * @return Advertisement
     */
    public function setEverlasting($everlasting)
    {
        $this->everlasting = $everlasting;

        return $this;
    }

    /**
     * Get everlasting
     *
     * @return boolean
     */
    public function getEverlasting()
    {
        return $this->everlasting;
    }

    /**
     * Set starts_at
     *
     * @param \DateTime $startsAt
     * @return Advertisement
     */
    public function setStartsAt($startsAt)
    {
        $this->starts_at = $startsAt;

        return $this;
    }

    /**
     * Get starts_at
     *
     * @return \DateTime
     */
    public function getStartsAt()
    {
        return $this->starts_at;
    }

    /**
     * Set expires_at
     *
     * @param \DateTime $expiresAt
     * @return Advertisement
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expires_at = $expiresAt;

        return $this;
    }

    /**
     * Get expires_at
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expires_at;
    }

    /**
     * Set frequency
     *
     * @param integer $frequency
     * @return Advertisement
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return integer
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Advertisement
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Advertisement
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set option
     *
     * @param \Siciarek\AdRotatorBundle\Entity\AdvertisementPrice $option
     * @return Advertisement
     */
    public function setOption(\Siciarek\AdRotatorBundle\Entity\AdvertisementPrice $option = null)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * Get option
     *
     * @return \Siciarek\AdRotatorBundle\Entity\AdvertisementPrice
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Set type
     *
     * @param \Siciarek\AdRotatorBundle\Entity\AdvertisementType $type
     * @return Advertisement
     */
    public function setType(\Siciarek\AdRotatorBundle\Entity\AdvertisementType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Siciarek\AdRotatorBundle\Entity\AdvertisementType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set client
     *
     * @param \Siciarek\AdRotatorBundle\Entity\Client $client
     * @return Advertisement
     */
    public function setClient(\Siciarek\AdRotatorBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Siciarek\AdRotatorBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}