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
    /**
     * @var \Phony\Server\Action\Get
     */
    protected $get;

    /**
     * @var \Phony\Server\Action\Add
     */
    protected $add;

    /**
     * @var \Phony\Server\Action\Flush
     */
    protected $flush;

    function __construct($add, $flush, $get)
    {
        $this->add = $add;
        $this->flush = $flush;
        $this->get = $get;
    }

    public function route(Request $request, HttpResponse $response)
    {
        if ($this->isSystemCall($request)) {

            return;
        }

        $mockedResponse = $this->get->action($request);
        $response->writeHead($mockedResponse->getHttpCode(), $mockedResponse->getHeaders());
        $response->write($mockedResponse->getBody());
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
