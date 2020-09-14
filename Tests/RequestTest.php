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

use PHPUnit\Framework\TestCase;
use Clyo\Request;

class RequestTest extends TestCase
{
    public function testGetUri()
    {
        $request = new Request('http://www.example.com/', 'get');
        $this->assertEquals('http://www.example.com/', $request->getUri(), '->getUri() returns the URI of the request');
    }

    public function testGetMethod()
    {
        $request = new Request('http://www.example.com/', 'get');
        $this->assertEquals('get', $request->getMethod(), '->getMethod() returns the method of the request');
    }

    public function testGetParameters()
    {
        $request = new Request('http://www.example.com/', 'get', ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $request->getParameters(), '->getParameters() returns the parameters of the request');
    }

    public function testGetFiles()
    {
        $request = new Request('http://www.example.com/', 'get', [], ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $request->getFiles(), '->getFiles() returns the uploaded files of the request');
    }

    public function testGetCookies()
    {
        $request = new Request('http://www.example.com/', 'get', [], [], ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $request->getCookies(), '->getCookies() returns the cookies of the request');
    }

    public function testGetServer()
    {
        $request = new Request('http://www.example.com/', 'get', [], [], [], ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $request->getServer(), '->getServer() returns the server parameters of the request');
    }

    public function testAllParameterValuesAreConvertedToString(): void
    {
        $parameters = [
            'foo' => 1,
            'bar' => [
                'baz' => 2,
            ],
        ];

        $expected = [
            'foo' => '1',
            'bar' => [
                'baz' => '2',
            ],
        ];

        $request = new Request('http://www.example.com/', 'get', $parameters);
        $this->assertSame($expected, $request->getParameters());
    }

    public function testAddServer()
    {
        $request = new Request('http://www.example.com/', 'get');
        $request->addServer('key', 'value');
        $server = $request->getServer();
        $this->assertArrayHasKey('key', $server);
        $this->assertEquals('value', $server['key']);
    }
}
