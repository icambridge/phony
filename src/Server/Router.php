<?php

namespace Phony\Server;
use React\Http\Request;

/**
 * Class Router
 * @package Phony\Server
 * @todo create interface
 */
class Router
{
    public function route(Request $request)
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
