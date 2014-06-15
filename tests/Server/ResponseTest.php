<?php

namespace Phony\Tests\Server;

use Phony\Server\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHttpCode()
    {
        $response = new Response(200);
        $this->assertEquals(200, $response->getHttpCode());
    }

    public function testGetContentType()
    {
        $contentType = "text/html";
        $response = new Response(200, $contentType);
        $this->assertEquals($contentType, $response->getContentType());
    }

    public function testGetHeaderHasContentType()
    {
        $contentType = "text/html";
        $response = new Response(200, $contentType);
        $this->assertArrayHasKey("Content-Type", $response->getHeaders());
        $this->assertEquals($contentType, $response->getHeaders()["Content-Type"]);
    }

    public function testGetHeadersHasExtraHeaders()
    {
        $key = "X-PHP";
        $value = "Codez";

        $response = new Response(200, "json", "", [$key => $value]);
        $this->assertArrayHasKey($key, $response->getHeaders());
        $this->assertEquals($value, $response->getHeaders()[$key]);
    }

    public function testGetBody()
    {
        $body = "Hello World";
        $response = new Response(200, '', $body);
        $this->assertEquals($body, $response->getBody());
    }
}
