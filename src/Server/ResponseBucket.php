<?php

namespace Phony\Server;

class ResponseBucket implements BucketInterface
{
    /**
     * @var Response[]
     */
    protected $responses = [];

    /**
     * @param $method
     * @param $uri
     * @param Response $response
     */
    public function add($method, $uri, $response)
    {
        $this->responses[$method][$uri] = $response;
    }

    /**
     * @param $method
     * @param $uri
     * @return Response
     */
    public function get($method, $uri)
    {
        if (!isset($this->responses[$method]) || !isset($this->responses[$method][$uri])) {
            return null;
        }

        return $this->responses[$method][$uri];
    }
}
