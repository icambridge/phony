<?php

namespace Phony\Tests\Server;

use Phony\Server\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Response
     */
    protected $response;

    public function setUp()
    {
        $this->response = new Response();
    }

    public function testSetsUpNamespace()
    {
        $response = new Response();
        $namespaceOne = $response->getNamespace();

        $this->assertEquals(10, strlen($namespaceOne));
        $this->assertTrue(is_string($namespaceOne));
        $this->assertEquals($response->getNamespace(), $response->getNamespace());
    }

    public function testFetchReturnsEmptyArrayWithCacheIsEmpty()
    {
        $output = $this->response->get("/test");
        $this->assertTrue(is_array($output));
        $this->assertEmpty($output);
    }

    public function testFetchReturnsContentsOfApcWhenNotEmpty()
    {
        $uri = "/test";
        $expectedOutput = [1];

        apc_store($this->response->getNamespace().$uri, $expectedOutput);

        $actualOutput = $this->response->get($uri);

        $this->assertNotEmpty($actualOutput);
        $this->assertEquals($expectedOutput, $actualOutput);

        apc_delete($this->response->getNamespace().$uri);
    }
}
