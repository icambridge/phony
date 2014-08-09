<?php

namespace Phony\Server;

use Phony\Server\Action\Action;
use Phony\Server\Request\ContextBuilderInterface;
use Phony\Server\Request\UrlMatcherFactory;
use Icambridge\Http\Request\BodiedRequest;
use Icambridge\Http\Response as HttpResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouteCollection;

class FrontController
{
    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @var Action
     */
    private $get;

    private $urlMatcherFactory;

    private $contextBuilder;

    public function __construct(
        RouteCollection $routeCollection,
        Action $getAction,
        ContextBuilderInterface $contextBuilder,
        UrlMatcherFactory $urlMatcherFactory
    ) {
        $this->routeCollection = $routeCollection;
        $this->get = $getAction;
        $this->urlMatcherFactory = $urlMatcherFactory;
        $this->contextBuilder = $contextBuilder;
    }

    public function route(BodiedRequest $bodiedRequest, HttpResponse $httpResponse)
    {
        try {
            $route = $this->getRoute($bodiedRequest);

            if (!$this->isValidRoute($route)) {
                return $this->serverBroke($httpResponse);
            }

            $response = $this->getAction($route)->action($bodiedRequest);
        } catch (ResourceNotFoundException $exception) {
            $response = $this->get->action($bodiedRequest);
        }

        if (!$response) {
            return $this->notFound($httpResponse);
        }

        return $this->returnResponse($httpResponse, $response);
    }

    private function returnResponse(HttpResponse $httpResponse, Response $response)
    {
        $httpResponse->writeHead($response->getHttpCode(), $response->getHeaders());
        $httpResponse->write($response->getBody());
        return true;
    }

    private function serverBroke(HttpResponse $response)
    {
        $response->writeHead(500, ["content-type" => "text/html"]);
        $response->write("Sorry bro, monkeys have broken stuff");
        return false;
    }

    private function notFound(HttpResponse $response)
    {
        $response->writeHead(404, ["content-type" => "text/html"]);
        $response->write("Sorry bro, can't find that.");
        return false;
    }

    /**
     * @param BodiedRequest $bodiedRequest
     * @return array
     */
    private function getRoute(BodiedRequest $bodiedRequest)
    {
        $context = $this->contextBuilder->build($bodiedRequest);
        $urlMatcher = $this->urlMatcherFactory->build($this->routeCollection, $context);
        return $urlMatcher->match($bodiedRequest->getPath());
    }

    /**
     * @param array $route
     * @return bool
     */
    private function isValidRoute(array $route)
    {
        if (!isset($route['action']) || !($route['action'] instanceof Action)) {
            return false;
        }

        return true;
    }

    /**
     * @param array $route
     *
     * @return Action
     */
    private function getAction(array $route)
    {
        return $route['action'];
    }
}
