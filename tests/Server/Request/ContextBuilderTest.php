<?php

namespace Phony\Test\Server\Request;

use Phony\Server\Request\ContextBuilder;

class ContextBuilderTest extends \PHPUnit_Framework_TestCase
{
    const METHOD = 'POST';
    const PATH   = '/hello/world';

    public function testGetRequestContext()
    {
        $request = $this->getMockBuilder('Icambridge\Http\Request\BodiedRequest')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method("getMethod")
            ->willReturn(self::METHOD);
        $request->expects($this->once())
            ->method("getPath")
            ->willReturn(self::PATH);

        $contextBuilder = new ContextBuilder();

        $context = $contextBuilder->build($request);

        $this->assertInstanceOf('Symfony\Component\Routing\RequestContext', $context);

        $this->assertEquals(self::METHOD, $context->getMethod());
        $this->assertEquals(self::PATH, $context->getPathInfo());
    }
}
