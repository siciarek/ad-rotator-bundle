<?php

namespace Siciarek\AdRotatorBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Siciarek\AdRotatorBundle\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;


/**
 * @Route("/sar")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/increment-clicks/{slug}", name="_sar_increment_clicks")
     */
    public function incrementClicksAction($slug)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository('SiciarekAdRotatorBundle:Ad')->findOneBy(array('slug' => $slug));

        if ($ad instanceof Ad) {
            $ad->setClicked($ad->getClicked() + 1);
            $em->persist($ad);
            $em->flush();

            return new Response('OK');
        }

        return new Response('');
    }

    /**
     * @Route("/file/{slug}", name="_sar_file")
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
     */
    public function jsonAction($count, $type)
    {
        $now = new \DateTime();

        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $ads = $em->getRepository('SiciarekAdRotatorBundle:Ad')
            ->createNamedQuery('available')
            ->setParameter('type', $type)
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

    public static function getAd($type, Container $container)
    {
        /**
         * @var EntityManager $em
         */
        $em = $container->get('doctrine.orm.entity_manager');

        /**
         * @var Query $query
         */
        $query = $em->getRepository('SiciarekAdRotatorBundle:Ad')
            ->createNamedQuery('available')
            ->setParameter('type', $type)
        ;

        $ads = $query->getResult();

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
