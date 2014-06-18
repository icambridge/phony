<?php

namespace Phony\Server\Parser;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnsResponseFilled()
    {
        $method = "POST";
        $uri = "/test";
        $contentType = "text/html";
        $httpCode = 200;
        $body = "text";

        $bodyArray = [
            "method" => $method,
            "uri" => $uri,
            "body" => $body,
            "content-type" => $contentType,
            "http-code" => $httpCode,
            "extra-headers" => []
        ];
        $bodyJson = json_encode($bodyArray);

        $parser = new Json();
        $response = $parser->parse($bodyJson);

        $this->assertEquals($method, $response->getMethod());
        $this->assertEquals($body, $response->getBody());
        $this->assertEquals($uri, $response->getUri());
        $this->assertEquals($httpCode, $response->getHttpCode());
    }
}
