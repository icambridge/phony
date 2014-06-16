<?php

namespace Phony\Server;

class ResponseBucketTest extends \PHPUnit_Framework_TestCase
{
    public function testAddResponse()
    {
        $method = "POST";
        $uri = "/test";
        $response = new Response(301);

        $responseBucket = new ResponseBucket();
        $responseBucket->add($method, $uri, $response);

        $reflected = new \ReflectionObject($responseBucket);
        $reflectedProperty = $reflected->getProperty("responses");
        $reflectedProperty->setAccessible(true);
        $value = $reflectedProperty->getValue($responseBucket);

        $this->assertNotEmpty($value);
        $this->assertArrayHasKey($method, $value);
        $this->assertArrayHasKey($uri, $value[$method]);
        $this->assertSame($response, $value[$method][$uri]);
    }

    public function testGetResponse()
    {
        $method = "POST";
        $uri = "/test";
        $response = new Response(301);

        $responseBucket = new ResponseBucket();


        $reflected = new \ReflectionObject($responseBucket);
        $reflectedProperty = $reflected->getProperty("responses");
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($responseBucket, [$method => [$uri => $response]]);

        $actualResponse = $responseBucket->get($method, $uri);

        $this->assertSame($response, $actualResponse);
    }
    public function testGetResponseReturnsNullsWhenDoesntExist()
    {
        $method = "POST";
        $uri = "/test";

        $responseBucket = new ResponseBucket();
        $actualResponse = $responseBucket->get($method, $uri);

        $this->assertNull($actualResponse);
    }
}
