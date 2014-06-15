<?php
// TODO Remove this crap!

require_once __DIR__ . "/../vendor/autoload.php";

class Counter {
    protected $count = 0;
    public function up() { $this->count++; }
    public function get() { return $this->count; }
}
$count = new Counter();
$app = function (\React\Http\Request $request,  \React\Http\Response $response) use ($count) {
    print $request->getPath().PHP_EOL;
    $response->writeHead(200, array('Content-Type' => 'text/plain'));
    $response->end("Hello World\n" . $count->get(). PHP_EOL);
    $count->up();
    print $count->get().PHP_EOL;
};

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$http = new React\Http\Server($socket, $loop);

$http->on('request', $app);
echo "Server running at http://127.0.0.1:1337\n";

$socket->listen(1337);
$loop->run();