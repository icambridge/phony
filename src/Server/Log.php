<?php

namespace Phony\Server;

use Icambridge\Http\Request\Request;

class Log implements LogInterface
{
    protected $requests = [];

    public function log(Request $request)
    {
        $uri = $request->getPath();
        $method = $request->getMethod();

        if (!isset($this->requests[$method]) || !isset($this->requests[$method][$uri])) {
            $this->requests[$method][$uri] = [];
        }
        $this->requests[$method][$uri][] = $request;
    }

    public function getHitCount($method, $uri)
    {
        return (isset($this->requests[$method][$uri])) ? count($this->requests[$method][$uri]) : 0;
    }

    public function flush()
    {
        $this->requests = [];
    }
}
