<?php

namespace Phony\Server;

class ResponseBucket
{
    protected $responses = [];

    public function add($method, $uri, $response)
    {
        $this->responses[$method][$uri] = $response;
    }
}
