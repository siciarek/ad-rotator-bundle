<?php

namespace Siciarek\AdRotatorBundle\Admin;

use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Symfony\Component\DependencyInjection\Container;

class DefaultAdmin extends Admin
{
    protected $translationDomain = 'SiciarekAdRotator';
    protected $exportDateFormat = 'Y-m-d H:i';
    protected $maxPerPage = 25;
    protected $maxPageLinks = 10;
    protected $supportsPreviewMode = false;

    public function getDataSourceIterator() {

        $datagrid = $this->getDatagrid();
        $datagrid->buildPager();

        $dataSourceIterator = $this->getModelManager()->getDataSourceIterator($datagrid, $this->getExportFields());

        if($dataSourceIterator instanceof \Exporter\Source\DoctrineORMQuerySourceIterator) {
            $dataSourceIterator->setDateTimeFormat($this->exportDateFormat);
        }

        return $dataSourceIterator;
    }

    public static function ping($host)
    {
        $cmd = sprintf('ping -c 1 -W 5 %s > /dev/null', escapeshellarg($host));
        if (PHP_OS === "WINNT") {
            $cmd = sprintf('ping -n 1 %s', escapeshellarg($host));
        }
        exec($cmd, $res, $rval);
        return $rval === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array(
            'xls',
            'csv',
        );
    }
}