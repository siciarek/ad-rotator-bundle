<?php

namespace Siciarek\AdRotatorBundle\Admin;

use Siciarek\AdRotatorBundle\Entity\AdvertisementType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class AdvertisementPriceAdmin extends DefaultAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $periods = array(
            'day',
            'week',
            'month',
        );

        $formMapper
            ->with('tabs.price.price')
                ->add('mainpage', null, array(
                    'label' => 'price.mainpage',
                    'required' => false,
                ))
                ->add('subpages', null, array(
                    'label' => 'price.subpages',
                    'required' => false,
                ))
                ->add('period', 'sonata_type_translatable_choice', array(
                    'label' => 'price.period',
                    'choices' => array_combine($periods, $periods),
                    'preferred_choices' => array($periods[1]),
                    'catalogue' => 'SiciarekAdRotator'
                ))
                ->add('duration', null, array(
                    'label' => 'price.duration',
                ))
                ->add('price', 'money', array(
                    'label' => 'price.price',
                    'currency' => 'PLN'
                ))
        ;
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
                    'edit'   => array(),
                    'delete' => array()
                ),
            ));
    }

}