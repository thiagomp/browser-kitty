<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Clyo\Tests;

use Clyo\CookieJar;
use Clyo\History;
use Clyo\KittyBrowser;
use Clyo\Response;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class TestKittyBrowser extends KittyBrowser
{
    protected $nextResponse = null;
    protected $nextScript = null;

    public function __construct(array $server = [], History $history = null, CookieJar $cookieJar = null)
    {
        $client = new MockHttpClient(function (string $method, string $url, array $options) {
            if (null === $this->nextResponse) {
                return new MockResponse();
            }

            return new MockResponse($this->nextResponse->getContent(), [
                'http_code' => $this->nextResponse->getStatusCode(),
                'response_headers' => $this->nextResponse->getHeaders(),
            ]);
        });
        parent::__construct($client);

        $this->setServerParameters($server);
        $this->history = $history ?? new History();
        $this->cookieJar = $cookieJar ?? new CookieJar();
    }

    public function setNextResponse(Response $response)
    {
        $this->nextResponse[] = $response;
    }

    public function setNextScript($script)
    {
        $this->nextScript = $script;
    }

    protected function doRequest($request): Response
    {
        if (null === $this->nextResponse) {
            return parent::doRequest($request);
        }

        $response = array_shift($this->nextResponse);

        return $response;
    }

    protected function getScript($request)
    {
        $r = new \ReflectionClass('Clyo\Response');
        $path = $r->getFileName();

        return <<<EOF
<?php

require_once('$path');

echo serialize($this->nextScript);
EOF;
    }
}
