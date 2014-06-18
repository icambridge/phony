<?php

namespace Phony\Tests\Server;

use Phony\Server\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMethod()
    {
        $method = "POST";
        $response = new Response($method);
        $this->assertEquals($method, $response->getMethod());
    }

    public function testGetUri()
    {
        $method = "POST";
        $uri = "/test";
        $response = new Response($method, $uri);
        $this->assertEquals($uri, $response->getUri());
    }

    public function testGetHttpCode()
    {
        $method = "POST";
        $uri = "/test";
        $statusCode = 200;
        $response = new Response($method, $uri, $statusCode);
        $this->assertEquals($statusCode, $response->getHttpCode());
    }

    public function testGetContentType()
    {
        $method = "POST";
        $uri = "/test";
        $contentType = "text/html";
        $response = new Response($method, $uri, 200, $contentType);
        $this->assertEquals($contentType, $response->getContentType());
    }

    public function testGetHeaderHasContentType()
    {
        $method = "POST";
        $uri = "/test";
        $contentType = "text/html";
        $response = new Response($method, $uri, 200, $contentType);
        $this->assertArrayHasKey("Content-Type", $response->getHeaders());
        $this->assertEquals($contentType, $response->getHeaders()["Content-Type"]);
    }

    public function testGetHeadersHasExtraHeaders()
    {
        $method = "POST";
        $uri = "/test";
        $key = "X-PHP";
        $value = "Codez";

        $response = new Response($method, $uri, 200, "json", "", [$key => $value]);
        $this->assertArrayHasKey($key, $response->getHeaders());
        $this->assertEquals($value, $response->getHeaders()[$key]);
    }

    public function testGetBody()
    {
        $method = "POST";
        $uri = "/test";
        $body = "Hello World";
        $response = new Response($method, $uri, 200, '', $body);
        $this->assertEquals($body, $response->getBody());
    }
}
