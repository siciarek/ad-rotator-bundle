<?php

namespace Siciarek\AdRotatorBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Siciarek\AdRotatorBundle\Entity\Ad;
use Siciarek\AdRotatorBundle\Entity\AdClick;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


/**
 * @Route("/sar")
 */
class DefaultController extends Controller
{

    protected function getGeoIpData($ip) {
        
        $data = array();

        return $data;

        $html = file_get_contents(sprintf('http://www.geoiptool.com/en/?IP=%s', $ip));
        $crawler = new Crawler($html);
        $temp = $crawler->filterXPath('//table[@class="tbl_style"][3]')->html();
        $temp = strip_tags($temp);

        $atemp = explode("\n", $temp);
        array_shift($atemp);

        $tdata = array();

        $key = null;

        foreach($atemp as $t) {
            if(preg_match('/:/', $t)) {
                $key = preg_replace('/:/', '', trim($t));
                $key = preg_replace('/\s+/', "_", $key);
                $key = strtolower($key);
                continue;
            }
            $tdata[$key][] = $t;
        }

        foreach($tdata as $key => $val) {
            $val = trim(implode(' ', $val));
            $data[$key] = (!empty($val) and $val !== '+' and $val !== '()') ? $val : null;
        }

        return $data;
    }

    protected function handleClick(Ad $ad, EntityManager $em) {
        $ip = $this->getRequest()->getClientIp();
        $browser = $this->getRequest()->attributes->get('_browser');
        $geo = $this->getGeoIpData($ip);

        $click = new AdClick();
        $click->setAd($ad);
        $click->setIp($ip);
        $click->setGeo($geo);
        $click->setBrowser($browser);

        $ad->setClicked($ad->getClicked() + 1);
        $em->persist($ad);
        $em->persist($click);
        $em->flush();
    }

    /**
     * For ads with embeded redirect function.
     *
     * @Route("/click/{slug}", name="_sar_increment_clicks")
     */
    public function incrementClicksAction($slug)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('SiciarekAdRotatorBundle:Ad')->findOneBy(array('slug' => $slug));

        if ($ad instanceof Ad) {
            $this->handleClick($ad, $em);

            return new Response('OK');
        }

        return new Response('');
    }

    /**
     * @Route("/display/{slug}", name="_sar_file")
     * @Cache(expires="tomorrow")
     */
    public function fileAction($slug)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('SiciarekAdRotatorBundle:Ad')->findOneBy(array('slug' => $slug));

        if (!$ad instanceof Ad) {
            throw $this->createNotFoundException();
        }

        $ad->setUploadRootDir($this->get('kernel')->getRootDir() . '/../web/');
        $filename = $ad->getAbsolutePath();

        $headers = array(
            'Content-Type' => mime_content_type($filename),
            'Content-Length' => filesize($filename),
        );

        return new Response(file_get_contents($filename), 200, $headers);
    }

    /**
     * @Route("/landing-page/{slug}", name="_sar_landing_page")
     */
    public function landingPageAction($slug)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('SiciarekAdRotatorBundle:Ad')->findOneBy(array('slug' => $slug));

        if (!$ad instanceof Ad) {
            throw $this->createNotFoundException();
        }

        $this->handleClick($ad, $em);

        return $this->redirect($ad->getLeadsTo());
    }

    /**
     * @Route("/{type}", defaults={"type":1}, requirements={"type":"^[1-9]\d*$"}, name="_sar_index")
     * @Template()
     */
    public function indexAction($type = 1)
    {
        return self::getAd($type, $this->container);
    }

    /**
     * @Route("/data/{type}/c/{count}", defaults={"type":1,"count":1}, requirements={"type":"^[1-9]\d*|__TYPE__$", "count":"^[1-9]\d*|__COUNT__$"}, name="_sar_data")
     */
    public function jsonAction($count, $type)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $ads = self::getAvailableAds($type);

        $data = array();

        if (count($ads) > 0) {

            $used = array();

            for ($i = 0; $i < $count; $i++) {

                $used = count($used) < count($ads) ? $used : array();

                do {
                    $aid = $this->getAdId($ads);
                } while (in_array($aid, $used));

                $used[] = $aid;

                /**
                 * @var Ad $ad
                 */
                $ad = $ads[$aid];
                // Inkrementacja wyświetleń:
                $ad->setDisplayed($ad->getDisplayed() + 1);
                $em->persist($ad);

                $data[] = self::getAdData($ad, $this->container);
            }

            $em->flush();
        }

        return new Response(json_encode($data), 200, array('Content-Type' => 'application/json'));
    }

    public static function getAvailableAds($type)
    {

        $container = \Siciarek\AdRotatorBundle\SiciarekAdRotatorBundle::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $sess = $container->get('session');

        /**
         * @var Query $query
         */
        $query = $em
            ->getRepository('SiciarekAdRotatorBundle:Ad')
            ->createNamedQuery('available')
            ->setParameter('type', $type);

        $ads = $query->getResult();

        return $ads;
    }

    public static function getAd($type, Container $container)
    {
        /**
         * @var EntityManager $em
         */
        $em = $container->get('doctrine.orm.entity_manager');


        $ads = self::getAvailableAds($type);

        $item = null;

        if (count($ads) > 0) {

            $aid = DefaultController::getAdId($ads);

            /**
             * @var Ad $ad
             */
            $ad = $ads[$aid];

            // Inkrementacja wyświetleń:
            $ad->setDisplayed($ad->getDisplayed() + 1);
            $em->persist($ad);
            $em->flush();

            $item = DefaultController::getAdData($ad, $container);
        }

        return array(
            'ad' => $item,
        );
    }

    /**
     * Returns ad data as a simple array, useful for ajax usage
     * @param Ad $ad
     * @return array
     */
    public static function getAdData(Ad $ad, Container $container)
    {
        $path = $ad->getPath();
        $src = null;
        $href = null;


        if ($ad->getLeadsTo() !== null) {
            $href = $container->get('router')->generate('_sar_landing_page', array('slug' => $ad->getSlug()), true);
        }

        if (preg_match('|^https?://|', $path)) {
            $src = $path;
        } else {
            $src = $container->get('router')->generate('_sar_file', array('slug' => $ad->getSlug()), true);;
        }

        return array(
            'filetype' => preg_match('/\.swf$/', $ad->getPath()) ? 'flash' : 'image',
            'pos' => preg_replace('/\D/', '', microtime()),
            'slug' => $ad->getSlug(),
            'type' => $ad->getType()->getId(),
            'title' => $ad->getTitle(),
            'params' => $ad->getType()->getDefinition(),
            'src' => $src,
            'href' => $href,
        );
    }

    /**
     * Returns random ad id
     * @param $ads
     * @return int|mixed
     */
    public static function getAdId($ads)
    {
        $aid = 0;

        foreach (range(1, 100) as $x) {
            $aid = array_rand($ads);
        }

        return $aid;
    }
}
