<?php

namespace Phony\Tests\Server;

use Phony\Server\Action\Add;
use Phony\Server\Action\Flush;
use Phony\Server\Action\Get;
use Phony\Server\Parser\Json;
use Phony\Server\Response;
use Phony\Server\ResponseBucket;
use Phony\Server\Router;
use Icambridge\Http\Request\BodiedRequest;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Router
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
        $this->router = new Router(new Add($responseBucket, $json), new Flush($responseBucket), new Get($responseBucket));
        $this->responseBucket = $responseBucket;
    }

    public function testIsSystemCallTrue()
    {
        $systemRequest = new BodiedRequest("GET", "/phony/status");

        $this->assertTrue($this->router->isSystemCall($systemRequest));
    }

    public  function testIsSystemCallFalse()
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
}
