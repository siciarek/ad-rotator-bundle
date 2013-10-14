<?php

namespace Siciarek\AdRotatorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sar/');

        $this->assertTrue($crawler->filter('html:contains("Advertisement Rotator")')->count() > 0);
    }
}
