<?php

namespace Siciarek\AdRotatorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Translation\Translator;

/**
 * AdPrice
 */
class AdPrice
{

    const WEEK = 'week';
    const DAY = 'day';
    const MAINPAGE = 'mainpage';
    const SUBPAGES = 'subpages';

    /**
     * @var Translator
     */
    protected $translator;

    public function __toString() {
        $this->translator = \Siciarek\AdRotatorBundle\SiciarekAdRotatorBundle::getContainer()->get('translator');


        if($this->getId() !== null) {
            return sprintf("%s (%s × %s) %s",
                $this->getPrice() > 0 ? sprintf('%0.2f zł', $this->getPrice()) : $this->translator->trans('free', array(), 'SiciarekAdRotator'),
                $this->translator->trans($this->getPeriod(), array(), 'SiciarekAdRotator'),
                $this->getDuration(),
                $this->getDescription()
            );
        }
        return '';
    }

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->setMainpage(true);
        $this->setSubpages(false);
        $this->setDuration(1);
        $this->setPrice(0);
        $this->setPeriod(self::WEEK);
        $this->type = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getDescription() {
        $this->translator = \Siciarek\AdRotatorBundle\SiciarekAdRotatorBundle::getContainer()->get('translator');
        $temp = array();
        if($this->getMainpage() === true) {
            $temp[] = $this->translator->trans(self::MAINPAGE, array(), 'SiciarekAdRotator');
        }
        if($this->getSubpages() === true) {
            $temp[] = $this->translator->trans(self::SUBPAGES, array(), 'SiciarekAdRotator');
        }

        return implode(' + ', $temp);
    }

    //////////////////////////////////////

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $mainpage;

    /**
     * @var boolean
     */
    private $subpages;

    /**
     * @var integer
     */
    private $duration;

    /**
     * @var string
     */
    private $period;

    /**
     * @var string
     */
    private $price;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $type;


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
     * Set mainpage
     *
     * @param boolean $mainpage
     * @return AdPrice
     */
    public function setMainpage($mainpage)
    {
        $this->mainpage = $mainpage;
    
        return $this;
    }

    /**
     * Get mainpage
     *
     * @return boolean 
     */
    public function getMainpage()
    {
        return $this->mainpage;
    }

    /**
     * Set subpages
     *
     * @param boolean $subpages
     * @return AdPrice
     */
    public function setSubpages($subpages)
    {
        $this->subpages = $subpages;
    
        return $this;
    }

    /**
     * Get subpages
     *
     * @return boolean 
     */
    public function getSubpages()
    {
        return $this->subpages;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     * @return AdPrice
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    
        return $this;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set period
     *
     * @param string $period
     * @return AdPrice
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    
        return $this;
    }

    /**
     * Get period
     *
     * @return string 
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return AdPrice
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
     * Add type
     *
     * @param \Siciarek\AdRotatorBundle\Entity\AdType $type
     * @return AdPrice
     */
    public function addType(\Siciarek\AdRotatorBundle\Entity\AdType $type)
    {
        $this->type[] = $type;
    
        return $this;
    }

    /**
     * Remove type
     *
     * @param \Siciarek\AdRotatorBundle\Entity\AdType $type
     */
    public function removeType(\Siciarek\AdRotatorBundle\Entity\AdType $type)
    {
        $this->type->removeElement($type);
    }

    /**
     * Get type
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getType()
    {
        return $this->type;
    }
}