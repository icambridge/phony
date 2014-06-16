<?php

namespace Phony\Server;

class ResponseBucket
{
    protected $responses = [];

    public function add($method, $uri, $response)
    {
        $this->responses[$method][$uri] = $response;
    }

    public function get($method, $uri)
    {
        if (!isset($this->responses[$method]) || !isset($this->responses[$method][$uri])) {
            return null;
        }

        return $this->responses[$method][$uri];
    }
}
