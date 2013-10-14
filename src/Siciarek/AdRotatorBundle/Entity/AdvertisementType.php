<?php

namespace Siciarek\AdRotatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdvertisementType
 */
class AdvertisementType
{
    public function __toString() {
        return $this->getName()?:'';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->advertisements = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $prices;

    /**
     * Add prices
     *
     * @param \Siciarek\AdRotatorBundle\Entity\AdvertisementPrice $prices
     * @return AdvertisementType
     */
    public function addPrice(\Siciarek\AdRotatorBundle\Entity\AdvertisementPrice $prices)
    {
        $prices->addType($this);
        $this->prices[] = $prices;

        return $this;
    }

    /**
     * Remove prices
     *
     * @param \Siciarek\AdRotatorBundle\Entity\AdvertisementPrice $prices
     */
    public function removePrice(\Siciarek\AdRotatorBundle\Entity\AdvertisementPrice $prices)
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
     * @var json
     */
    private $definition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $advertisements;


    /**
     * Set id
     *
     * @param integer $id
     * @return AdvertisementType
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
     * @return AdvertisementType
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
     * @param json $definition
     * @return AdvertisementType
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    
        return $this;
    }

    /**
     * Get definition
     *
     * @return json 
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Add advertisements
     *
     * @param \Siciarek\AdRotatorBundle\Entity\Advertisement $advertisements
     * @return AdvertisementType
     */
    public function addAdvertisement(\Siciarek\AdRotatorBundle\Entity\Advertisement $advertisements)
    {
        $this->advertisements[] = $advertisements;
    
        return $this;
    }

    /**
     * Remove advertisements
     *
     * @param \Siciarek\AdRotatorBundle\Entity\Advertisement $advertisements
     */
    public function removeAdvertisement(\Siciarek\AdRotatorBundle\Entity\Advertisement $advertisements)
    {
        $this->advertisements->removeElement($advertisements);
    }

    /**
     * Get advertisements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdvertisements()
    {
        return $this->advertisements;
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