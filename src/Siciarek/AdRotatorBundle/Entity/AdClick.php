<?php

namespace Siciarek\AdRotatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdClick
 */
class AdClick
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $browser;

    /**
     * @var string
     */
    private $geo;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \Siciarek\AdRotatorBundle\Entity\Ad
     */
    private $ad;


    /**
     * Set id
     *
     * @param integer $id
     * @return AdClick
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

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
     * Set ip
     *
     * @param string $ip
     * @return AdClick
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set browser
     *
     * @param string $browser
     * @return AdClick
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
    
        return $this;
    }

    /**
     * Get browser
     *
     * @return string 
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Set geo
     *
     * @param string $geo
     * @return AdClick
     */
    public function setGeo($geo)
    {
        $this->geo = $geo;
    
        return $this;
    }

    /**
     * Get geo
     *
     * @return string 
     */
    public function getGeo()
    {
        return $this->geo;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return AdClick
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
     * Set ad
     *
     * @param \Siciarek\AdRotatorBundle\Entity\Ad $ad
     * @return AdClick
     */
    public function setAd(\Siciarek\AdRotatorBundle\Entity\Ad $ad = null)
    {
        $this->ad = $ad;
    
        return $this;
    }

    /**
     * Get ad
     *
     * @return \Siciarek\AdRotatorBundle\Entity\Ad 
     */
    public function getAd()
    {
        return $this->ad;
    }
}
