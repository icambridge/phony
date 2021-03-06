<?php

namespace Phony\Server\Action;

use Icambridge\Http\Request\BodiedRequest;
use Phony\Server\BucketInterface;
use Phony\Server\Parser\ParserInterface;
use Phony\Server\Response;

class Add implements Action
{
    /**
     * @var \Phony\Server\BucketInterface
     */
    protected $responseBucket;

    /**
     * @var \Phony\Server\Parser\ParserInterface
     */
    protected $parser;

    public function __construct(BucketInterface $responseBucket, ParserInterface $parser)
    {
        $this->parser = $parser;
        $this->responseBucket = $responseBucket;
    }

    public function action(BodiedRequest $request)
    {
        $response = $this->parser->parse($request->getBody());
        $this->responseBucket->add($response->getMethod(), $response->getUri(), $response);

        return new Response('GET', '/', 201, 'application/json', '{"status": "success"}');
    }
}
