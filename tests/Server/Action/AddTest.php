<?php

namespace Phony\Tests\Server\Action;

use Phony\Server\Action\Add;
use Phony\Server\Parser\Json;
use Phony\Server\ResponseBucket;

class AddTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMock()
    {
        $request = $this->getMockBuilder("\\Icambridge\\Http\\Request\\BodiedRequest")
            ->disableOriginalConstructor()
            ->getMock();

        $method = "POST";
        $uri = "/test";
        $body = "text";
        $statusCode = 200;
        $extraHeaders = [];
        $contentType = "text/html";

        $jsonArray = [
            "method" => $method,
            "uri" => $uri,
            "body" => $body,
            "content-type" => $contentType,
            "http-code" => $statusCode,
            "extra-headers" => $extraHeaders
        ];
        $bodyJson = json_encode($jsonArray);

        $request->expects($this->once())
            ->method("getBody")
            ->will($this->returnValue($bodyJson));

        $responseBucket = new ResponseBucket();
        $parser = new Json();

        $mock = new Add($responseBucket, $parser);
        $mock->action($request);

        $response = $responseBucket->get($method, $uri);
        $this->assertEquals($body, $response->getBody());
        $this->assertEquals($method, $response->getMethod());
        $this->assertEquals($uri, $response->getUri());
        $this->assertEquals($statusCode, $response->getHttpCode());
    }
}
