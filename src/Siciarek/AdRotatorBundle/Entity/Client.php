<?php

namespace Siciarek\AdRotatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 */
class Client
{
    public function __toString() {
        return $this->getName()?:'';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setEnabled(true);
        $this->ads = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $invoice_name;

    /**
     * @var string
     */
    private $invoice_nip;

    /**
     * @var string
     */
    private $invoice_address;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ads;

    /**
     * Set id
     *
     * @param integer $id
     * @return Client
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
     * Set enabled
     *
     * @param boolean $enabled
     * @return Client
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
     * Set name
     *
     * @param string $name
     * @return Client
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
     * Set email
     *
     * @param string $email
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Client
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set invoice_name
     *
     * @param string $invoiceName
     * @return Client
     */
    public function setInvoiceName($invoiceName)
    {
        $this->invoice_name = $invoiceName;
    
        return $this;
    }

    /**
     * Get invoice_name
     *
     * @return string 
     */
    public function getInvoiceName()
    {
        return $this->invoice_name;
    }

    /**
     * Set invoice_nip
     *
     * @param string $invoiceNip
     * @return Client
     */
    public function setInvoiceNip($invoiceNip)
    {
        $this->invoice_nip = $invoiceNip;
    
        return $this;
    }

    /**
     * Get invoice_nip
     *
     * @return string 
     */
    public function getInvoiceNip()
    {
        return $this->invoice_nip;
    }

    /**
     * Set invoice_address
     *
     * @param string $invoiceAddress
     * @return Client
     */
    public function setInvoiceAddress($invoiceAddress)
    {
        $this->invoice_address = $invoiceAddress;
    
        return $this;
    }

    /**
     * Get invoice_address
     *
     * @return string 
     */
    public function getInvoiceAddress()
    {
        return $this->invoice_address;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Client
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
     * @return Client
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
     * Add ads
     *
     * @param \Siciarek\AdRotatorBundle\Entity\Ad $ads
     * @return Client
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
}