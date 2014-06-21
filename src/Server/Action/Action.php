<?php

namespace Phony\Server\Action;

use Icambridge\Http\Request\BodiedRequest;

interface Action
{
    public function action(BodiedRequest $request);
}
