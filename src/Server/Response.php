<?php

namespace Phony\Server;

/**
 * Class Response
 * @package Phony\Server
 * @todo create interface
 */
class Response
{
    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var int
     */
    protected $httpCode;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var array
     */
    protected $extraHeaders = [];

    /**
     * @param $httpCode
     */
    public function __construct($method = "GET", $uri = "/", $httpCode = 200, $contentType = "text/html", $body = "", array $extraHeaders = [])
    {
        $this->method= $method;
        $this->uri = $uri;
        $this->httpCode = $httpCode;
        $this->contentType = $contentType;
        $this->body = $body;
        $this->extraHeaders = $extraHeaders;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headers = ["Content-Type" => $this->contentType];
        $headers = array_merge($headers, $this->extraHeaders);
        return $headers;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}
