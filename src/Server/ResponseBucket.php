<?php

namespace Phony\Server;

class ResponseBucket implements BucketInterface
{
    /**
     * @var Response[]
     */
    protected $responses = [];

    /**
     * @param string $method
     * @param string $uri
     * @param Response $response
     */
    public function add($method, $uri, $response)
    {
        $this->responses[$method][$uri] = $response;
    }

    /**
     * @param string $method
     * @param string $uri
     * @return Response
     */
    public function get($method, $uri)
    {
        if (!isset($this->responses[$method]) || !isset($this->responses[$method][$uri])) {
            return null;
        }

        return $this->responses[$method][$uri];
    }

    public function flush()
    {
        $this->responses = [];
    }
}
