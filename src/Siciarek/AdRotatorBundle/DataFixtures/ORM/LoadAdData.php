<?php

namespace Siciarek\AdRotatorBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Siciarek\AdRotatorBundle\Entity\AdvertisementPrice;
use Siciarek\AdRotatorBundle\Entity\AdvertisementType;
use Siciarek\AdRotatorBundle\Entity\Client;

class LoadAdData extends BaseFixture
{
    protected $order = 100;
    public $count = 0;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
        $p = 0;
        foreach ($this->getData('AdvertisementPrice') as $o) {
            $obj = new AdvertisementPrice();
            $obj->setMainpage($o['mainpage']);
            $obj->setSubpages($o['subpages']);
            $obj->setPeriod($o['period']);
            $obj->setDuration($o['duration']);

            $om->persist($obj);
            $this->setReference('ad-price-' . (++$p), $obj);
        }

        foreach ($this->getData('AdvertisementType') as $o) {
            $obj = new AdvertisementType();
            $obj->setId($o['id']);
            $obj->setName($o['name']);
            $obj->setDefinition($o['definition']);

            foreach (range(1, $p) as $i) {
                $obj->addPrice($this->getReference('ad-price-' . $i));
            }

            $om->persist($obj);
        }

        foreach ($this->getData('Client') as $o) {
            $obj = new Client();
            $obj->setName($o['name']);
            $obj->setEmail($o['email']);
            $obj->setPhone($o['phone']);
            $obj->setInvoiceName($o['invoice_name']);
            $obj->setInvoiceNip($o['invoice_nip']);
            $obj->setInvoiceAddress($o['invoice_address']);

            $om->persist($obj);
        }

        $om->flush();
    }
}