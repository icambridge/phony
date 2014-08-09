<?php

namespace Phony\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Stream\Stream;
use Symfony\Component\Process\PhpProcess;
use Symfony\Component\Process\Process;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $endpoint = "http://localhost:1337/";

    /**
     * @var PhpProcess
     */
    protected $serverProcess;


    public function startServer()
    {

        $fileLocation = __DIR__ . "/server.php";
        $cmd = "php {$fileLocation}";
        $this->serverProcess = new Process($cmd);
        $this->serverProcess->start();

        $client = new Client(["base_url" => $this->endpoint]);
        $isRunning = false;
        $tries = 0;
        do {
            try{
                $client->get("/phony/flush");
                $isRunning = true;
            } catch (RequestException $e) {
                usleep(100);
                $tries++;
            }
        } while($isRunning == false && $tries <= 100);

    }

    public function stopServer()
    {
        $this->serverProcess->stop();
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
