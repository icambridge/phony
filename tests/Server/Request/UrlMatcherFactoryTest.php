<?php

namespace Phony\Test\Server\Request;

use Phony\Server\Request\UrlMatcherFactory;
use Symfony\Component\Routing\RequestContext;

class UrlMatcherFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $request = new RequestContext();

        $routeCollection = $this->getMockBuilder('Symfony\Component\Routing\RouteCollection')
            ->getMock();

        $factory = new UrlMatcherFactory();
        $urlMatcher = $factory->build($routeCollection, $request);

        $this->assertInstanceOf('Symfony\Component\Routing\Matcher\UrlMatcher', $urlMatcher);
    }
}