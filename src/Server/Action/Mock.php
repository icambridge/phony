<?php

namespace Phony\Server\Action;

use Phony\Server\ResponseBucket;
use React\Http\Request;

class Mock implements Action
{
    protected $responseBucket;

    public function __construct(ResponseBucket $responseBucket)
    {
        $this->responseBucket = $responseBucket;
    }

    public function action(Request $request)
    {
        return $this->responseBucket->get($request->getMethod(), $request->getPath());
    }
}
