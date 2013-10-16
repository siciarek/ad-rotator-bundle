<?php

namespace Siciarek\AdRotatorBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;
use EWZ\Bundle\TextBundle\Templating\Helper\TextHelper;
use Siciarek\AdRotatorBundle\Controller\DefaultController;
use Siciarek\AdRotatorBundle\Entity\Advertisement;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use CG\Core\ClassUtils;


class SiciarekAdRotatorExtension extends \Twig_Extension
{

    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'siciarek_ad_rotator_twig_extension';
    }

    /**
     * Filters declaration
     */
    public function getFilters()
    {
        return array(

        );
    }

    /**
     * Functions declaration
     */
    public function getFunctions()
    {
        return array(
            'display_ad' => new \Twig_SimpleFunction('display_ad', array($this, 'displayAd'), array('needs_environment' => true, 'is_safe' => array('html'))),
        );
    }


    /**
     * Custom methods
     */

    /**
     * @param \Twig_Environment $twig
     * @param int $type
     * @return string
     */
    public function displayAd(\Twig_Environment $twig, $type = 1, $static = false) {
        $params = DefaultController::getAd($type, $this->container);
        $params['static'] = $static;
        return $twig->render('SiciarekAdRotatorBundle:Default:index.html.twig', $params);
    }
}
