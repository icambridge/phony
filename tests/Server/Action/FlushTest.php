<?php

namespace Phony\Tests\Server\Action;

use Phony\Server\Action\Flush;

class FlushTest extends \PHPUnit_Framework_TestCase
{
    public function testHitsFlush()
    {
        $request = $this->getMockBuilder("\\Icambridge\\Http\\Request\\BodiedRequest")
            ->disableOriginalConstructor()
            ->getMock();
        $responseBucket = $this->getMock("\\Phony\\Server\\ResponseBucket");

        $responseBucket->expects($this->once())
            ->method("flush");

        $mock = new Flush($responseBucket);
        $mock->action($request);
    }
}
