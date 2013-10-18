<?php

namespace Siciarek\AdRotatorBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SiciarekAdRotatorBundle extends Bundle
{
    /**
     * @var ContainerInterface
     */
    private static $containerInstance = null;

    public function setContainer(ContainerInterface $container = null) {
        parent::setContainer($container);
        self::$containerInstance = $container;
    }

    /**
     * @return null|ContainerInterface
     */
    public static function getContainer()
    {
        return self::$containerInstance;
    }
}
