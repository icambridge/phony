<?php

require_once __DIR__ . "/../vendor/autoload.php";

$app = function (\React\Http\Request $request,  \React\Http\Response $response) {

    $request->on("data", function($data) use ($request, $response) {
            echo $data.PHP_EOL;
            $response->end("Bye World\n". PHP_EOL);
        } );

    $response->writeHead(200, array('Content-Type' => 'text/plain'));
    $response->end("Hello World\n". PHP_EOL);
    print "End".PHP_EOL;
};

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$http = new React\Http\Server($socket, $loop);

$http->on('request', $app);
echo "Server running at http://127.0.0.1:1337\n";

$socket->listen(1337);
$loop->run();