<?php

namespace Phony\Tests\Server;

use Phony\Server\ResponseList;

class ResponseListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResponseList
     */
    protected $responseList;

    /**
     * @var string
     */
    protected $uri = "/test";

    public function setUp()
    {
        $this->responseList = new ResponseList();
    }

    public function tearDown()
    {
        apc_delete($this->responseList->getNamespace().$this->uri);
    }

    public function testSetsUpNamespace()
    {
        $responseList = new ResponseList();
        $namespaceOne = $responseList->getNamespace();

        $this->assertEquals(10, strlen($namespaceOne));
        $this->assertTrue(is_string($namespaceOne));
        $this->assertEquals($responseList->getNamespace(), $responseList->getNamespace());
    }

    public function testPopReturnsEmptyArrayWithCacheIsEmpty()
    {
        $output = $this->responseList->shift($this->uri);
        $this->assertEmpty($output);
    }

    public function testPopReturnsContentsOfApcWhenNotEmpty()
    {
        $expectedOutput = 1;

        apc_store($this->responseList->getNamespace().$this->uri, [$expectedOutput]);

        $actualOutput = $this->responseList->shift($this->uri);

        $this->assertNotEmpty($actualOutput);
        $this->assertEquals($expectedOutput, $actualOutput);
    }

    public function testPopReturnsContentsOfApcWhenNotEmptyRemovesFromCache()
    {
        $expectedOutput = 1;
        $expectedStored = 2;

        apc_store($this->responseList->getNamespace().$this->uri, [$expectedOutput, $expectedStored]);

        $actualOutput = $this->responseList->shift($this->uri);

        $this->assertNotEmpty($actualOutput);
        $this->assertEquals($expectedOutput, $actualOutput);

        $stored = apc_fetch($this->responseList->getNamespace().$this->uri);

        $this->assertEquals([$expectedStored], $stored);
    }

    public function testAddInsertsResponse()
    {
        $input = 1;
        $expectedOutput = [$input];

        $this->responseList->push($this->uri, $input);

        $actualOutput = apc_fetch($this->responseList->getNamespace().$this->uri);

        $this->assertNotNull($actualOutput);
        $this->assertNotEmpty($actualOutput);
        $this->assertEquals($expectedOutput, $actualOutput);
    }

    public function testAddInsertsResponseMultiple()
    {
        $input = 2;
        $expectedOutput = [1,$input];

        apc_store($this->responseList->getNamespace().$this->uri, [1]);

        $this->responseList->push($this->uri, $input);

        $actualOutput = apc_fetch($this->responseList->getNamespace().$this->uri);

        $this->assertNotNull($actualOutput);
        $this->assertNotEmpty($actualOutput);
        $this->assertEquals($expectedOutput, $actualOutput);
    }
}
