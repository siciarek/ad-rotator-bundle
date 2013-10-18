<?php

namespace Siciarek\AdRotatorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/sar/');

        $this->assertTrue($crawler->filter('html:contains("img")')->count() > 0);
    }

    public function testData()
    {
        $client = static::createClient();

        /**
         * Non existing ad type
         */
        $client->request('GET', '/sar/data/1000/c');
        $json = $client->getResponse()->getContent();
        $this->assertEquals(json_encode(json_decode($json)), $json, 'No proper JSON was returned.');
        $data = json_decode($json, true);
        $this->assertTrue(is_array($data));
        $this->assertEquals(0, count($data));

        /**
         * Existing ad type
         */
        $client->request('GET', '/sar/data/1/c');
        $json = $client->getResponse()->getContent();
        $this->assertEquals(json_encode(json_decode($json)), $json, 'No proper JSON was returned.');
        $data = json_decode($json, true);
        $this->assertTrue(is_array($data));
        $this->assertEquals(1, count($data));

        $item = $data[0];
        $this->assertTrue(is_array($item));

        $keys = array('filetype', 'pos', 'slug', 'type', 'title', 'params', 'src', 'href');

        foreach($keys as $key) {
            $this->assertArrayHasKey($key, $item, "Item has no attribute \"$key\".");
        }

        $this->assertEquals(1, $item['type']);
    }
}
