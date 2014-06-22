<?php

namespace Phony\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $endpoint = "http://localhost:1337/";

    protected $serverProcess;

    public function startServer()
    {
        $fileLocation = __DIR__ . "/server.php";
        $cmd = "php {$fileLocation}";
        $pipe = [];
        $this->serverProcess = proc_open($cmd, [0 => ["pipe", "r"]], $pipe);
        sleep(1);
    }

    public function stopServer()
    {
        proc_terminate($this->serverProcess, 9);
    }

    public function flush()
    {
        $client = new Client(["base_url" => $this->endpoint]);
        $client->get("/phony/flush");
    }

    public function createRequest($method, $uri, $body, $statusCode = 200, $contentType = "text/html", $headers = [])
    {
        $jsonArray = [
            "method" => $method,
            "uri" => $uri,
            "body" => $body,
            "content-type" => $contentType,
            "http-code" => $statusCode,
            "extra-headers" => $headers
        ];
        $jsonString = json_encode($jsonArray);
        $stream = Stream::factory($jsonString);

        $client = new Client(["base_url" => $this->endpoint]);
        $request = $client->createRequest("POST", "/phony/add");
        $request->setBody($stream);
        $client->send($request);
    }
}
