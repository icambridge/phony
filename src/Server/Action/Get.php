<?php

namespace Phony\Server\Action;

use Phony\Server\BucketInterface;
use Icambridge\Http\Request\BodiedRequest;

class Get implements Action
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
        return $this->responseBucket->get($request->getMethod(), $request->getPath());
    }
}
