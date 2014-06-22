<?php

// todo factor into actual cmd

function includeIfExists($file)
{
    if (file_exists($file)) {
        return include $file;
    }
}
if ((!$loader = includeIfExists(__DIR__.'/../../vendor/autoload.php')) &&
    (!$loader = includeIfExists(__DIR__.'/../../../../autoload.php'))) {
    fwrite(STDERR,
        'You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL
    );
    exit(-1);
}

use Phony\Server\ResponseBucket;
use Phony\Server\Parser\Json;
use Phony\Server\Action\Add;
use Phony\Server\Action\Flush;
use Phony\Server\Action\Get;
use Phony\Server\Router;

$responseBucket = new ResponseBucket();
$json = new Json();
$router = new Router(new Add($responseBucket, $json), new Flush($responseBucket), new Get($responseBucket));

$app = function (\Icambridge\Http\Request\BodiedRequest $request,  \Icambridge\Http\Response $response) use ($router) {
    $router->route($request, $response);
    $response->end();
};

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);

$requestFactory = new Icambridge\Http\Request\Factory\BodiedRequestFactory();
$messageParser = new Guzzle\Parser\Message\MessageParser();
$requestParser = new Icambridge\Http\Request\Parser($requestFactory, $messageParser);
$http = new Icambridge\Http\Server($socket, $requestParser);

$http->on('request', $app);
echo "Server running at http://127.0.0.1:1337\n";

$socket->listen(1337);
$loop->run();