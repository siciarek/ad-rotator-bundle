<?php

namespace Siciarek\AdRotatorBundle\Admin;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Siciarek\AdRotatorBundle\Form\Type\DatePickerType;
use Siciarek\AdRotatorBundle\Entity\Ad;
use Siciarek\AdRotatorBundle\Entity\AdType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdClickAdmin extends DefaultAdmin
{
    protected $baseRoutePattern = 'sar/click';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(array('list', 'show'));
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('created_at', null, array(
                'label' => 'click.created_at',
                'format' => 'Y-m-d H:i'
            ))
            ->add('ad', null, array(
                'label' => 'click.ad',
            ))
            ->add('ip', null, array(
                'label' => 'click.ip',
            ))
            ->add('geo', null, array(
                'label' => 'click.geo',
                'template' => 'SiciarekAdRotatorBundle:CRUD:show_array.html.twig',
            ))
            ->add('browser', null, array(
                'label' => 'click.browser',
                'template' => 'SiciarekAdRotatorBundle:CRUD:show_array.html.twig',
            ))
        ;

    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('created_at', null, array(
                'label' => 'click.created_at',
                'format' => 'Y-m-d H:i'
            ))
            ->add('ad', null, array(
                'label' => 'click.ad',
            ))
            ->add('ip', null, array(
                'label' => 'click.ip',
            ))
            ->add('geo', null, array(
                'label' => 'click.geo',
                'template' => 'SiciarekAdRotatorBundle:CRUD:list_geo.html.twig',
            ))
            ->add('browser', null, array(
                'label' => 'click.browser',
                'template' => 'SiciarekAdRotatorBundle:CRUD:list_browser.html.twig',
            ))
            ->add('_action', 'actions', array(
                'label' => 'general.actions',
                'actions' => array(
                    'show' => array(),
                ),
            ))
        ;
    }
}
