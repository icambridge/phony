<?php

namespace Phony\Server;

interface BucketInterface
{
    public function add($method, $uri, $response);

    public function get($method, $uri);
}
