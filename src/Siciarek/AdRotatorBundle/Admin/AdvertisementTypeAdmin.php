<?php

namespace Siciarek\AdRotatorBundle\Admin;

use Siciarek\AdRotatorBundle\Entity\AdvertisementPriceRepository;
use Siciarek\AdRotatorBundle\Entity\AdvertisementType;
use Doctrine\ORM\Query;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class AdvertisementTypeAdmin extends DefaultAdmin
{
    protected static $defaultDefinition = array(
        'width' => 468,
        'height' => 60,
        'formats' => 'jpeg;jpg;gif;png;svg;swf',
        'rotateAfter' => 30,
    );

    protected function getDefinitionAsString(AdvertisementType $obj)
    {
        $definition = $obj->getId() ? $obj->getDefinition() : self::$defaultDefinition;
        $definition = json_encode($definition);
        $definition = \Siciarek\AdRotatorBundle\Utils\Json::format($definition);

        return $definition;
    }

    protected function encodeDefinition(AdvertisementType $obj)
    {
        $definition = $obj->getDefinition();
        $definition = json_decode($definition);

        return $obj->setDefinition($definition);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        /**
         * @var AdvertisementPriceRepository $repository
         */
        $repository = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('SiciarekAdRotatorBundle:AdvertisementPrice');

        /**
         * @var Query $prices
         */
        $prices = $repository->createNamedQuery('ordered');

        $formMapper
            ->add('name', null, array(
                'label' => 'type.name',
                'trim' => true,
            ))
            ->add('definition', 'textarea', array(
                'label' => 'type.definition',
                'trim' => true,
                'error_bubbling' => false,
                'data' => $this->getDefinitionAsString($this->getSubject()),
                'attr' => array('rows' => 8),
            ))
            ->add('prices', 'sonata_type_model', array(
                    'trim' => true,
                    'expanded' => true,
                    'multiple' => true,
                    'compound' => true,
                    'by_reference' => false,
                    'query' =>  $prices,
                )
            )
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, array(
                'label' => 'type.id',
            ))
            ->add('name', null, array(
                'label' => 'type.name',
            ))
            ->add('definition', null, array(
                'label' => 'type.definition',
                'template' => 'SiciarekAdRotatorBundle:CRUD:list_array.html.twig',
            ))
            ->add('prices')
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
        $em = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager');

        /**
         * @var Query $query
         */
        $query = $em->getRepository('SiciarekAdRotatorBundle:AdvertisementType')
            ->createNamedQuery('maxid');
        $maxid = intval($query->getSingleScalarResult());
        $object->setId($maxid + 1);
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('definition');
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $this->encodeDefinition($object);
    }

}