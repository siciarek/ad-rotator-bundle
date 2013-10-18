<?php

namespace Siciarek\AdRotatorBundle\Admin;

use Siciarek\AdRotatorBundle\Doctrine\DBAL\Types\NipType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class ClientAdmin extends DefaultAdmin
{
    protected $baseRoutePattern = 'sar/client';

    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper->with('tabs.client.basic_data');
        $formMapper->add('enabled', null, array(
            'label' => 'client.enabled',
            'required' => false,
        ));
        $formMapper->add('name', null, array(
            'label' => 'client.name',
        ));
        $formMapper->add('email', 'email', array(
            'label' => 'client.email',
        ));
        $formMapper->add('phone', null, array(
            'label' => 'client.phone',
        ));

        $formMapper->with('tabs.client.invoice_data');
        $formMapper->add('invoice_name', null, array(
            'label' => 'client.invoice_name',
        ));
        $formMapper->add('invoice_nip', null, array(
            'label' => 'client.invoice_nip',
        ));
        $formMapper->add('invoice_address', null, array(
            'label' => 'client.invoice_address',
        ));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('enabled', null, array(
                'label' => 'client.enabled',
                'editable' => true,
            ))
            ->addIdentifier('id', null, array(
                'label' => 'client.id',
            ))
            ->addIdentifier('name', null, array(
                'label' => 'client.name',
            ))
            ->add('email', null, array(
                'label' => 'client.email',
            ))
            ->add('phone', null, array(
                'label' => 'client.phone',
            ))
            ->add('created_at', null, array(
                'label' => 'client.created_at',
            ))
            ->add('_action', 'actions', array(
                'label' => 'general.actions',
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array()
                ),
            ));
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->with('tabs.client.basic_data');
        $showMapper->add('enabled', null, array(
            'label' => 'client.enabled',
        ));
        $showMapper->add('name', null, array(
            'label' => 'client.name',
        ));
        $showMapper->add('email', null, array(
            'label' => 'client.email',
        ));
        $showMapper->add('phone', null, array(
            'label' => 'client.phone',
        ));
        $showMapper->with('tabs.client.invoice_data');
        $showMapper->add('invoice_name', null, array(
            'label' => 'client.invoice_name',
        ));
        $showMapper->add('invoice_nip', null, array(
            'label' => 'client.invoice_nip',
        ));
        $showMapper->add('invoice_address', null, array(
            'label' => 'client.invoice_address',
        ));
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name');
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $nip = $object->getInvoiceNip();

        if (!NipType::isValid($nip)) {
            $msg = sprintf('Wartość "%s" nie jest poprawnym numerem NIP.', $nip);
            $errorElement->addViolation($msg);
        }
    }
}