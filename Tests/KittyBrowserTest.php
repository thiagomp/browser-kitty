<?php

/*
 * This file extends the Symfony Browser-Kit package.
 *
 * (c) Thiago Melo <thiagomp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Clyo\Tests;

use PHPUnit\Framework\TestCase;
use Clyo\Response;

class KittyBrowserTest extends TestCase {

    public function getBrowser(array $server = [], History $history = null, CookieJar $cookieJar = null) {
        return new TestKittyBrowser($server, $history, $cookieJar);
    }

    public function testRedirectCount() {
        $client = $this->getBrowser();

        $client->setNextResponse(new Response('', 302, ['Location' => 'http://www.kittyexample.com/meow']));
        $client->setNextResponse(new Response('<html><a href="/meow">meow</a></html>', 200));
        $client->request('GET', 'http://www.kittyexample.com/foo/foobar');
        $redirInfo = $client->getRedirectInfo();

        $this->assertEquals(1, $client->getRedirectCount(), '->getRedirectCount() expecting 1 redirect');
        $this->assertIsArray($redirInfo, '->getRedirectInfo() returns an array');
        $this->assertContainsOnlyInstancesOf(Response::class, $redirInfo, '->getRedirectInfo() is an array of Response objects');

        $client->setNextResponse(new Response('', 302, ['Location' => 'http://www.kittyexample.com']));
        $client->setNextResponse(new Response('', 302, ['Location' => 'https://www.kittyexample.com/meow']));
        $client->setNextResponse(new Response('<html><a href="/meow">meow</a></html>', 200));
        $client->request('GET', 'http://kittyexample.com');
        $redirInfo = $client->getRedirectInfo();

        $this->assertEquals(2, $client->getRedirectCount(), '->getRedirectCount() expecting 2 redirects');
        $this->assertIsArray($redirInfo, '->getRedirectInfo() returns an array');
        $this->assertContainsOnlyInstancesOf(Response::class, $redirInfo, '->getRedirectInfo() is an array of Response objects');
    }

    public function testUpgradeInsecureRequests() {

        $client = $this->getBrowser();
        $client->upgradeInsecureRequests(true);
        $client->request('GET', 'http://www.kittyexample.com');
        $server = $client->getRequest()->getServer();
        $this->assertArrayHasKey('http_upgrade-insecure-requests', $server);
    }
}