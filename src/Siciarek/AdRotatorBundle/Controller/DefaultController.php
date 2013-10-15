<?php

namespace Siciarek\AdRotatorBundle\Controller;

use Doctrine\ORM\EntityManager;
use Siciarek\AdRotatorBundle\Entity\Advertisement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Route("/sar")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/file/{slug}", name="_ads_file")
     */
    public function fileAction($slug)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('SiciarekAdRotatorBundle:Advertisement')->findOneBy(array('slug' => $slug));

        if (!$ad instanceof Advertisement) {
            throw $this->createNotFoundException();
        }

        $ad->setUploadRootDir($this->get('kernel')->getRootDir() . '/../web/' );
        $filename = $ad->getAbsolutePath();

        $headers = array(
            'Content-Type' => mime_content_type($filename),
            'Content-Length' => filesize($filename),
        );

        $resp = new Response();
        $resp->headers->add($headers);
        $resp->sendHeaders();
        readfile($filename);
    }

    /**
     * @Route("/landing-page/{slug}", name="_ads_landing_page")
     * @Template()
     */
    public function landingPageAction($slug)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('SiciarekAdRotatorBundle:Advertisement')->findOneBy(array('slug' => $slug));

        if (!$ad instanceof Advertisement) {
            throw $this->createNotFoundException();
        }

        $ad->setClicked($ad->getClicked() + 1);
        $em->persist($ad);
        $em->flush();

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
     * @Route("/data/{type}/c/{count}", defaults={"type":1,"count":1}, requirements={"type":"^[1-9]\d*$", "count":"^[1-9]\d*$"}, name="_sar_data")
     * @Template()
     */
    public function jsonAction($count, $type)
    {
        $now = new \DateTime();

        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository('SiciarekAdRotatorBundle:Advertisement')
            ->createNamedQuery('available')
            ->setParameter('type', $type)
            ->setParameter('now', $now)
            ->getResult();

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
                 * @var Advertisement $ad
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

    public static function getAd($type, $container) {

        $now = new \DateTime();

        $em = $container->get('doctrine.orm.entity_manager');

        $query = $em->getRepository('SiciarekAdRotatorBundle:Advertisement')
            ->createNamedQuery('available')
            ->setParameter('type', $type)
            ->setParameter('now', $now);

        $ads = $query->getResult();

        $item = null;

        if (count($ads) > 0) {

            $aid = DefaultController::getAdId($ads);

            /**
             * @var Advertisement $ad
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
     * @param Advertisement $ad
     * @return array
     */
    public static function getAdData(Advertisement $ad, $container)
    {
        $path = $ad->getPath();
        $src = null;
        $href = null;


        if ($ad->getLeadsTo() !== null) {
            $href = $container->get('router')->generate('_ads_landing_page', array('slug' => $ad->getSlug()), true);
        }

        $baseurl = preg_replace('|^(.*?)/sar/.*?$|', '$1', $href);

        if (preg_match('|^https?://|', $path)) {
            $src = $path;
        } else {
            $src = $container->get('router')->generate('_ads_file', array('slug' => $ad->getSlug()), true);;
        }

        return array(
            'filetype' => preg_match('/\.swf$/', $ad->getPath()) ? 'flash' : 'image',
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
