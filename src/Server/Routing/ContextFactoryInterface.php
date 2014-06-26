<?php

namespace Phony\Server\Routing;

use Icambridge\Http\Request\Request;

interface ContextFactoryInterface
{
    public function get(Request $request);
}
