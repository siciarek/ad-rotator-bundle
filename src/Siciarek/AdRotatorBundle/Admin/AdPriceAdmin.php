<?php

namespace Siciarek\AdRotatorBundle\Admin;

use Doctrine\ORM\EntityManager;
use Siciarek\AdRotatorBundle\Entity\AdPrice;
use Siciarek\AdRotatorBundle\Entity\AdType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class AdPriceAdmin extends DefaultAdmin
{
    protected $baseRoutePattern = 'sar/price';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $periods = array(
            AdPrice::WEEK,
            AdPrice::DAY,
        );

        $formMapper->with('tabs.price.price');
        $formMapper->add('mainpage', null, array(
            'label' => 'price.mainpage',
            'required' => false,
        ));
        $formMapper->add('subpages', null, array(
            'label' => 'price.subpages',
            'required' => false,
        ));
        $formMapper->add('price', 'money', array(
            'label' => 'price.price',
            'currency' => 'PLN'
        ));
        $formMapper->add('period', 'sonata_type_translatable_choice', array(
            'label' => 'price.period',
            'choices' => array_combine($periods, $periods),
            'catalogue' => 'SiciarekAdRotator'
        ));
        $formMapper->add('duration', null, array(
            'label' => 'price.duration',
        ));

        if ($this->getSubject()->getId() === null) {
            $formMapper->add('all_types', 'checkbox', array(
                'label' => 'price.all_types',
                'required' => false,
                'mapped' => false,
                'data' => true,
            ));
        }
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('description', null, array(
                'label' => 'price.description',
            ))
            ->add('mainpage', null, array(
                'label' => 'price.mainpage',
                'editable' => true,
            ))
            ->add('subpages', null, array(
                'label' => 'price.subpages',
                'editable' => true,
            ))
            ->add('period', null, array(
                'label' => 'price.period',
            ))
            ->add('duration', null, array(
                'label' => 'price.duration',
            ))
            ->add('price', null, array(
                'label' => 'price.price',
            ))
            ->add('_action', 'actions', array(
                'label' => 'general.actions',
                'actions' => array(
                    'edit' => array(),
                    'delete' => array()
                ),
            ));
    }

    public function prePersist($object)
    {
        if ($this->getForm()->get('all_types')->getNormData() === false) {
            return;
        }

        /**
         * @var AdPrice $object
         */

        /**
         * @var EntityManager $em
         */
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');
        $typesRepo = $em->getRepository('SiciarekAdRotatorBundle:AdType');

        $types = $typesRepo->findAll();

        /**
         * @var AdType $t
         */
        foreach ($types as $t) {

            if ($object->getType()->contains($t)) {
                continue;
            }

            $t->addPrice($object);
            $em->persist($t);
        }
    }
}
