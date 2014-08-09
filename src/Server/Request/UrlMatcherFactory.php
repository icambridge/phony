<?php

namespace Phony\Server\Request;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class UrlMatcherFactory
{
    /**
     * @param RouteCollection $routeCollection
     * @param RequestContext $context
     * @return UrlMatcher
     */
    function build(RouteCollection $routeCollection, RequestContext $context)
    {
        return new UrlMatcher($routeCollection, $context);
    }
}
