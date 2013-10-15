<?php

namespace Siciarek\AdRotatorBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseFixture extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var Container
     */
    protected $container;
    protected $em;
    protected $order = 1;
    protected $count = 1;
    protected $data_dir;
    protected $test = false;


    protected function getData($key, $fileName = null)
    {
        $fileName = $fileName === null ? $key : $fileName;
        $file     = $this->data_dir . $fileName . '.yml';

        if (!(is_file($file) === true and is_readable($file) === true)) {
            throw new \Exception('No such file: ' . $file);
        }

        $data = Yaml::parse($file);

        if (!(is_array($data) and array_key_exists($key, $data))) {
            throw new \Exception('Invalid data in file: ' . $file);
        }

        return $data[$key];
    }

    function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface $container A ContainerInterface instance
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->data_dir  = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        $this->container = $container;
        $this->em        = $this->container->get('doctrine.orm.entity_manager');
    }
}