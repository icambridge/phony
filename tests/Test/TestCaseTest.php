<?php

namespace Phony\Tests\Test;

use GuzzleHttp\Client;
use Phony\Test\TestCase;

class TestCaseTest extends \PHPUnit_Framework_TestCase
{
    protected $endpoint = "http://localhost:1337/";
    /**
     * @var TestCase
     */
    protected $case;

    public function setUp()
    {
        $this->case = new TestCase();
        $this->case->startServer();
    }

    public function tearDown()
    {
        $this->case->stopServer();
    }

    public function testCreateRequest()
    {
        $this->case->createRequest("GET", "/hello-world", "HELLO");

        $client = new Client(["base_url" => $this->endpoint]);
        $response = $client->get("/hello-world");
        $this->assertEquals("HELLO", $response->getBody());
    }

    public function testFlush()
    {
        $this->case->createRequest("GET", "/hello-world", "HELLO");

        $client = new Client(["base_url" => $this->endpoint]);
        $response = $client->get("/hello-world");
        $this->assertEquals("HELLO", $response->getBody());
        $this->case->flush();
        $response = $client->get("/hello-world");
        $this->assertNotEquals("HELLO", $response->getBody());
    }
}
