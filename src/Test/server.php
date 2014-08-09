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
    fwrite(
        STDERR,
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
use Phony\Server\FrontController;
use Phony\Server\Request\ContextBuilder;
use Phony\Server\Request\UrlMatcherFactory;
use Symfony\Component\Routing\Route;

$responseBucket = new ResponseBucket();
$json = new Json();
// Move to seperate class.
$routeCollection = new \Symfony\Component\Routing\RouteCollection();
$routeCollection->add('system_flush', new Route('/phony/flush', ['action' => new Flush($responseBucket)]));
$routeCollection->add('system_add', new Route('/phony/add', ['action' => new Add($responseBucket, $json)]));

$frontController = new FrontController(
    $routeCollection,
    new Get($responseBucket),
    new ContextBuilder(),
    new UrlMatcherFactory()
);

$app = function (\Icambridge\Http\Request\BodiedRequest $request,  \Icambridge\Http\Response $response) use ($frontController) {
    $frontController->route($request, $response);
    $response->end();
};

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);

$requestFactory = new Icambridge\Http\Request\Factory\BodiedRequestFactory();
$messageParser = new Guzzle\Parser\Message\MessageParser();
$requestParser = new Icambridge\Http\Request\Parser($requestFactory, $messageParser);
$http = new Icambridge\Http\Server($socket, $requestParser);

$http->on('request', $app);

$socket->listen(1337);
$loop->run();