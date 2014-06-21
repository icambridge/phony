<?php

require_once __DIR__ . "/../vendor/autoload.php";

$app = function (\Icambridge\Http\Request\BodiedRequest $request,  \Icambridge\Http\Response $response) {

    $response->writeHead(200, array('Content-Type' => 'text/plain'));

    $response->end("Hello World\n". PHP_EOL);
    print $request->getBody().PHP_EOL;
};

$loop = React\EventLoop\Factory::create()
;
$socket = new React\Socket\Server($loop);

$requestFactory = new Icambridge\Http\Request\Factory\BodiedRequestFactory();
$messageParser = new Guzzle\Parser\Message\MessageParser();
$requestParser = new Icambridge\Http\Request\Parser($requestFactory, $messageParser);
$http = new Icambridge\Http\Server($socket, $requestParser);

$http->on('request', $app);
echo "Server running at http://127.0.0.1:1337\n";

$socket->listen(1337);
$loop->run();