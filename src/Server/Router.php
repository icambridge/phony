<?php

namespace Phony\Server;

use Icambridge\Http\Request\Request;
use Icambridge\Http\Response as HttpResponse;

/**
 * Class Router
 * @package Phony\Server
 * @todo create interface
 */
class Router
{
    public function route(Request $request, HttpResponse $response)
    {

    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isSystemCall(Request $request)
    {
        $path = $request->getPath();

        return (preg_match("~^/phony/~isU", $path) !== 0);
    }
}
