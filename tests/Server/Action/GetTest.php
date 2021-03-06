<?php

namespace Phony\Tests\Server\Action;

use Phony\Server\Action\Get;

class GetTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMock()
    {
        $request = $this->getMockBuilder("\\Icambridge\\Http\\Request\\BodiedRequest")
            ->disableOriginalConstructor()
            ->getMock();

        $response = $this->getMock("\\Phony\\Server\\Response");
        $responseBucket = $this->getMock("\\Phony\\Server\\ResponseBucket");

        $method = "POST";
        $uri = "/test";

        $request->expects($this->once())
            ->method("getPath")
            ->will($this->returnValue($uri));
        $request->expects($this->once())
            ->method("getMethod")
            ->will($this->returnValue($method));

        $responseBucket->expects($this->once())
            ->method("get")
            ->with($this->equalTo($method), $this->equalTo($uri))
            ->will($this->returnValue($response));

        $mock = new Get($responseBucket);
        $actualResponse = $mock->action($request);
        $this->assertSame($response, $actualResponse);
    }
}
