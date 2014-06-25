<?php

namespace Phony\Server;

use Icambridge\Http\Request\Request;

interface LogInterface
{
    public function log(Request $request);

    public function getHitCount($method, $uri);

    public function flush();
}
