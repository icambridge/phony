<?php

namespace Phony\Server\Action;

use React\Http\Request;

interface Action
{
    public function action(Request $request);
}
