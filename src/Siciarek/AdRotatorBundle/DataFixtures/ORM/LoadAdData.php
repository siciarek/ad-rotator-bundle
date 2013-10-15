<?php

namespace Siciarek\AdRotatorBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Siciarek\AdRotatorBundle\Entity\Advertisement;
use Siciarek\AdRotatorBundle\Entity\AdvertisementPrice;
use Siciarek\AdRotatorBundle\Entity\AdvertisementType;
use Siciarek\AdRotatorBundle\Entity\Client;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

            $this->setReference('ad-type-' . $o['id'], $obj);
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

            $this->setReference('ad-client-' . $o['name'], $obj);
            $om->persist($obj);
        }

        $om->flush();

        foreach ($this->getData('Advertisement') as $o) {
            $obj = new Advertisement();
            $obj->setEnabled($o['enabled']);
            $obj->setType($this->getReference('ad-type-' . $o['type']));
            $obj->setOption($this->getReference('ad-price-' . $o['option']));
            $obj->setClient($this->getReference('ad-client-' . $o['client']));
            $obj->setTitle($o['title']);
            $obj->setLeadsTo($o['leads_to']);

            $def = $obj->getType()->getDefinition();

            $forig = sprintf('%s%dx%d/%s', $this->getBinariesPath(),
                $def['width'], $def['height'],
                $o['uploaded_file']
            );

            $fcopy = $this->getBinariesPath() . 'copy.' . $o['uploaded_file'];

            copy($forig, $fcopy);
            $file = new UploadedFile($fcopy, $o['uploaded_file'], mime_content_type($fcopy), filesize($fcopy), null, true);

            $obj->setUploadRootDir($this->container->get('kernel')->getRootDir() . '/../web/' );
            $obj->setUploadedFile($file);

            $om->persist($obj);
        }

        $om->flush();
    }
}