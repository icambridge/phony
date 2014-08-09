<?php

namespace Phony\Server\Request;

use Icambridge\Http\Request\BodiedRequest;
use Symfony\Component\Routing\RequestContext;

class ContextBuilder implements ContextBuilderInterface
{
    /**
     * @param BodiedRequest $request
     * @return RequestContext
     */
    public function build(BodiedRequest $request)
    {
        $context = new RequestContext();
        $context->setMethod($request->getMethod());
        $context->setPathInfo($request->getPath());

        return $context;
    }
}
