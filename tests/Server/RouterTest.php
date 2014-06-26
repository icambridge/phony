<?php

namespace Phony\Tests\Server;

use Phony\Server\Action\Add;
use Phony\Server\Action\Flush;
use Phony\Server\Action\Get;
use Phony\Server\Parser\Json;
use Phony\Server\Response;
use Phony\Server\ResponseBucket;
use Phony\Server\FrontController;
use Icambridge\Http\Request\BodiedRequest;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FrontController
     */
    protected $router;

    /**
     * @var ResponseBucket
     */
    protected $responseBucket;

    public function setUp()
    {
        $responseBucket = new ResponseBucket();
        $json = new Json();
        $this->router = new FrontController(new Add($responseBucket, $json), new Flush($responseBucket), new Get($responseBucket));
        $this->responseBucket = $responseBucket;
    }

    public function testIsSystemCallTrue()
    {
        $systemRequest = new BodiedRequest("GET", "/phony/status");

        $this->assertTrue($this->router->isSystemCall($systemRequest));
    }

    public function testIsSystemCallFalse()
    {
        $nonSystemRequest = new BodiedRequest("GET", "/hello-world");

        $this->assertFalse($this->router->isSystemCall($nonSystemRequest));
    }

    public function testHitsGetAndResponds()
    {
        $method = "GET";
        $uri = "/hello-world";
        $statusCode = 200;
        $contentType = "text/html";
        $body = "hello world";

        $response = new Response($method, $uri, $statusCode, $contentType, $body);
        $this->responseBucket->add($method, $uri, $response);
        $httpResponse = $this->getMockBuilder("\\Icambridge\\Http\\Response")
            ->disableOriginalConstructor()
            ->getMock();
        $nonSystemRequest = new BodiedRequest("GET", "/hello-world");

        $httpResponse->expects($this->once())
            ->method("writeHead")
            ->with($this->equalTo($statusCode), $this->equalTo($response->getHeaders()));
        $httpResponse->expects($this->once())
            ->method("write")
            ->with($this->equalTo($body));

        $this->router->route($nonSystemRequest, $httpResponse);
    }
    public function testHitsGet404()
    {
        $method = "GET";
        $uri = "/hello-world";
        $statusCode = 200;
        $contentType = "text/html";
        $body = "hello world";

        $response = new Response($method, $uri, $statusCode, $contentType, $body);
        $this->responseBucket->add($method, $uri, $response);
        $httpResponse = $this->getMockBuilder("\\Icambridge\\Http\\Response")
            ->disableOriginalConstructor()
            ->getMock();
        $nonSystemRequest = new BodiedRequest("GET", "/bye");


        $httpResponse->expects($this->once())
            ->method("writeHead")
            ->with($this->equalTo(404), $this->equalTo(["content-type" => "text/html"]));
        $httpResponse->expects($this->once())
            ->method("write")
            ->with($this->equalTo("Sorry bro, can't find that."));

        $this->router->route($nonSystemRequest, $httpResponse);
    }

    public function testHitsFlush()
    {
        $method = "GET";
        $uri = "/hello-world";
        $statusCode = 200;
        $contentType = "text/html";
        $body = "hello world";

        $response = new Response($method, $uri, $statusCode, $contentType, $body);
        $this->responseBucket->add($method, $uri, $response);
        $httpResponse = $this->getMockBuilder("\\Icambridge\\Http\\Response")
            ->disableOriginalConstructor()
            ->getMock();
        $nonSystemRequest = new BodiedRequest("GET", "/phony/flush");


        $body = '{"status": "success"}';
        $httpResponse->expects($this->once())
            ->method("writeHead")
            ->with($this->equalTo($statusCode), $this->equalTo(["content-type" => "application/json"]));
        $httpResponse->expects($this->once())
            ->method("write")
            ->with($this->equalTo($body));

        $this->router->route($nonSystemRequest, $httpResponse);

        $response = $this->responseBucket->get($method, $uri);
        $this->assertNull($response);
    }

    public function testSystemAdd()
    {
        $method = "GET";
        $uri = "/hello-world";
        $statusCode = 200;
        $contentType = "text/html";
        $extraHeaders = [];
        $responseBody = "Hello World";
        $jsonArray = [
            "method" => $method,
            "uri" => $uri,
            "body" => $responseBody,
            "content-type" => $contentType,
            "http-code" => $statusCode,
            "extra-headers" => $extraHeaders
        ];

        $bodyJson = json_encode($jsonArray);
        $body = '{"status": "success"}';
        $httpResponse = $this->getMockBuilder("\\Icambridge\\Http\\Response")
            ->disableOriginalConstructor()
            ->getMock();
        $nonSystemRequest = new BodiedRequest("POST", "/phony/add", [], '1.1', [], $bodyJson);

        $httpResponse->expects($this->once())
            ->method("writeHead")
            ->with($this->equalTo($statusCode), $this->equalTo(["content-type" => "application/json"]));
        $httpResponse->expects($this->once())
            ->method("write")
            ->with($this->equalTo($body));

        $this->router->route($nonSystemRequest, $httpResponse);

        $response = $this->responseBucket->get($method, $uri);
        $this->assertNotEmpty($response);
        $this->assertInstanceOf("Phony\\Server\\Response", $response);
    }
}
