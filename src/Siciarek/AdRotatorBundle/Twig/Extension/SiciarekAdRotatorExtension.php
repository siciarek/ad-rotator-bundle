<?php

namespace Siciarek\AdRotatorBundle\Twig\Extension;

use Assetic\Test\Filter\JSMinFilterTest;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Siciarek\AdRotatorBundle\Controller\DefaultController;
use Siciarek\AdRotatorBundle\Entity\Ad;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SiciarekAdRotatorExtension extends \Twig_Extension
{

    private static $firstAdSet = false;
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
        return array();
    }

    /**
     * Functions declaration
     */
    public function getFunctions()
    {
        return array(
            'display_ad' => new \Twig_SimpleFunction('display_ad', array($this, 'displayAd'), array('needs_environment' => true, 'is_safe' => array('html'))),
            'display_single_ad' => new \Twig_SimpleFunction('display_single_ad', array($this, 'displaySingleAd'), array('needs_environment' => true, 'is_safe' => array('html'))),
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param int $type
     * @return string
     */
    public function displaySingleAd(\Twig_Environment $twig, $id)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        /**
         * @var Query $query
         */
        $query = $em->getRepository('SiciarekAdRotatorBundle:Ad')
            ->createNamedQuery('single')
            ->setParameter('id', intval($id))
        ;

        $ads = $query->getResult();

        if (count($ads) > 0) {
            /**
             * @var Ad $ad
             */
            $ad = $ads[0];
            $ad->setDisplayed($ad->getDisplayed() + 1);
            $em->persist($ad);
            $em->flush();

            $params['ad'] = DefaultController::getAdData($ad, $this->container);
            $params['ad']['single'] = true;
            $params['static'] = true;
            return $twig->render('SiciarekAdRotatorBundle:Default:index.html.twig', $params);
        }

        return '';
    }

    /**
     * Custom methods
     */

    /**
     * @param \Twig_Environment $twig
     * @param int $type
     * @return string
     */
    public function displayAd(\Twig_Environment $twig, $type = 1, $static = false, $timeout = 30)
    {
        $params = DefaultController::getAd($type, $this->container);
        $params['static'] = $static;
        $output = $twig->render('SiciarekAdRotatorBundle:Default:index.html.twig', $params);

        $router = $this->container->get('router');

        if(self::$firstAdSet === false) {
            $jsparams = array(
                'sarRotateAfter' => $timeout,
                'sarDataUrl' =>  $router->generate('_sar_data', array('type' => '__TYPE__', 'count' => '__COUNT__'), true),
                'sarIncrementClicksUrl' => $router->generate('_sar_increment_clicks', array('slug' => '__SLUG__'), true),
            );
            $javascript = $twig->render('SiciarekAdRotatorBundle:Default:script.html.twig', $jsparams);
            $output = $javascript . $output;
            self::$firstAdSet = true;
        }

        return $output;
    }
}
