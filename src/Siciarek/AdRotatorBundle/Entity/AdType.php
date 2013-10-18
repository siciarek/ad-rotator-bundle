<?php

namespace Siciarek\AdRotatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdType
 */
class AdType
{
    public function __toString() {
        return $this->getName()?:'';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ads = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $prices;

    /**
     * Add prices
     *
     * @param \Siciarek\AdRotatorBundle\Entity\AdPrice $prices
     * @return AdType
     */
    public function addPrice(\Siciarek\AdRotatorBundle\Entity\AdPrice $prices)
    {
        $prices->addType($this);
        $this->prices[] = $prices;

        return $this;
    }

    /**
     * Remove prices
     *
     * @param \Siciarek\AdRotatorBundle\Entity\AdPrice $prices
     */
    public function removePrice(\Siciarek\AdRotatorBundle\Entity\AdPrice $prices)
    {
        $prices->removeType($this);
        $this->prices->removeElement($prices);
    }

/////////////////////////////////////////////////////////////////////////////

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $definition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ads;


    /**
     * Set id
     *
     * @param integer $id
     * @return AdType
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
     * Set name
     *
     * @param string $name
     * @return AdType
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set definition
     *
     * @param array $definition
     * @return AdType
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    
        return $this;
    }

    /**
     * Get definition
     *
     * @return array
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Add ads
     *
     * @param \Siciarek\AdRotatorBundle\Entity\Ad $ads
     * @return AdType
     */
    public function addAd(\Siciarek\AdRotatorBundle\Entity\Ad $ads)
    {
        $this->ads[] = $ads;
    
        return $this;
    }

    /**
     * Remove ads
     *
     * @param \Siciarek\AdRotatorBundle\Entity\Ad $ads
     */
    public function removeAd(\Siciarek\AdRotatorBundle\Entity\Ad $ads)
    {
        $this->ads->removeElement($ads);
    }

    /**
     * Get ads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAds()
    {
        return $this->ads;
    }


    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrices()
    {
        return $this->prices;
    }
}