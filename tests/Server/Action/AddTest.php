<?php

namespace Phony\Tests\Server\Action;

use Phony\Server\Action\Add;

class AddTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMock()
    {
        $this->markTestIncomplete("Need to look at react");
        $request = $this->getMockBuilder("\\React\\Http\\Request")->disableOriginalConstructor()->getMock();
        $response = $this->getMock("\\Phony\\Server\\Response");
        $responseBucket = $this->getMock("\\Phony\\Server\\ResponseBucket");

        $method = "POST";
        $uri = "/test";
        $body = [
            "method" => $method,
            "uri" => $uri,
            "body" => "text",
            "content-type" => "text/html",
            "status-code" => 200,
            "extra-headers" => []
        ];
        $bodyJson = json_encode($body);

        $request->expects($this->once())
            ->method("getBody")
            ->will($this->returnValue($bodyJson));

        $responseBucket->expects($this->once())
            ->method("add")
            ->with($this->equalTo($method), $this->equalTo($uri), $this->equalTo($response));


        $mock = new Add($responseBucket);
        $mock->action($request);
    }
}
