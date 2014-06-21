<?php

namespace Phony\Tests\Server;

use Phony\Server\Router;
use Icambridge\Http\Request\Request;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSystemCallTrue()
    {
        $systemRequest = new Request("GET", "/phony/status");

        $router = new Router();
        $this->assertTrue($router->isSystemCall($systemRequest));
    }

    public  function testIsSystemCallFalse()
    {
        $nonSystemRequest = new Request("GET", "/hello-world");

        $router = new Router();
        $this->assertFalse($router->isSystemCall($nonSystemRequest));
    }
}
