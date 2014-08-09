<?php

namespace Phony\Server\Action;

use Phony\Server\BucketInterface;
use Icambridge\Http\Request\BodiedRequest;
use Phony\Server\Response;

class Flush implements Action
{
    /**
     * @var BucketInterface
     */
    protected $responseBucket;

    public function __construct(BucketInterface $responseBucket)
    {
        $this->responseBucket = $responseBucket;
    }

    public function action(BodiedRequest $request)
    {
        $this->responseBucket->flush();

        return new Response('GET', '/', 204, 'application/json', '{"status": "success"}');
    }
}