<?php

namespace Phony\Server;

use Icambridge\Http\Request\BodiedRequest;
use Icambridge\Http\Response as HttpResponse;

/**
 * Class FrontController
 */
class FrontController
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

    public function __construct($add, $flush, $get)
    {
        $this->add = $add;
        $this->flush = $flush;
        $this->get = $get;
    }

    public function route(BodiedRequest $request, HttpResponse $response)
    {
        if ($this->isSystemCall($request)) {
            $this->systemCalls($request, $response);
            return;
        }

        $mockedResponse = $this->get->action($request);
        if (!$mockedResponse) {
            // TODO move to prebuilt class
            $response->writeHead(404, ["content-type" => "text/html"]);
            $response->write("Sorry bro, can't find that.");
            return;
        }
        $response->writeHead($mockedResponse->getHttpCode(), $mockedResponse->getHeaders());
        $response->write($mockedResponse->getBody());
    }


    /**
     * @param Request $request
     * @return bool
     */
    public function isSystemCall(BodiedRequest $request)
    {
        $path = $request->getPath();

        return (preg_match("~^/phony/~isU", $path) !== 0);
    }

    public function systemCalls(BodiedRequest $request, HttpResponse $response)
    {
        if (preg_match('~/phony/add~isu', $request->getPath()) && $request->getMethod() == "POST") {
            $this->add->action($request);
            $this->successResponse($response);
            return;
        } elseif (preg_match('~/phony/flush~isu', $request->getPath())) {
            $this->flush->action($request);
            $this->successResponse($response);
            return;
        }
    }

    protected function successResponse(HttpResponse $response)
    {
        $successBody = '{"status": "success"}';
        $response->writeHead(200, ["content-type" => "application/json"]);
        $response->write($successBody);
    }
}
