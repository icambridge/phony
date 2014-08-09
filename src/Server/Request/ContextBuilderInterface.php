<?php

namespace Phony\Server\Request;

use Icambridge\Http\Request\BodiedRequest;

interface ContextBuilderInterface
{
    public function build(BodiedRequest $request);
}
