<?php

namespace Phony\Tests\Server;

use Phony\Server\Log;

class LogTest extends \PHPUnit_Framework_TestCase
{
    public function testLogReturnsOne()
    {
        $uri = "/hello/world";
        $method = "GET";

        $request = $this->getMockBuilder("Icambridge\\Http\\Request\\Request")
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method("getPath")
            ->will($this->returnValue($uri));
        $request->expects($this->once())
            ->method("getMethod")
            ->will($this->returnValue($method));

        $logger =  new Log();
        $logger->log($request);

        $this->assertEquals(1, $logger->getHitCount($method, $uri));
    }

    public function testLogKeepsArrayOfRequest()
    {
        $uri = "/hello/world";
        $method = "GET";

        $request = $this->getMockBuilder("Icambridge\\Http\\Request\\Request")
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method("getPath")
            ->will($this->returnValue($uri));
        $request->expects($this->once())
            ->method("getMethod")
            ->will($this->returnValue($method));

        $logger =  new Log();
        $logger->log($request);

        $reflectedLogger = new \ReflectionObject($logger);
        $requestsProperty = $reflectedLogger->getProperty("requests");
        $requestsProperty->setAccessible(true);
        $requestsValue = $requestsProperty->getValue($logger);

        $this->assertTrue(is_array($requestsValue));
        $this->assertArrayHasKey($method, $requestsValue);
        $this->assertArrayHasKey($uri, $requestsValue[$method]);
        $this->assertCount(1, $requestsValue[$method][$uri]);
        $this->assertArrayHasKey(0, $requestsValue[$method][$uri]);
        $this->assertSame($request, $requestsValue[$method][$uri][0]);
    }

    public function testGetHitCountReturnsZero()
    {
        $uri = "/hello/world";
        $method = "GET";
        $logger =  new Log();

        $this->assertEquals(0, $logger->getHitCount($method, $uri));
    }

    public function testFlush()
    {

        $uri = "/hello/world";
        $method = "GET";

        $request = $this->getMockBuilder("Icambridge\\Http\\Request\\Request")
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method("getPath")
            ->will($this->returnValue($uri));
        $request->expects($this->once())
            ->method("getMethod")
            ->will($this->returnValue($method));

        $logger =  new Log();
        $logger->log($request);
        $this->assertEquals(1, $logger->getHitCount($method, $uri));
        $logger->flush();
        $this->assertEquals(0, $logger->getHitCount($method, $uri));
    }
}
