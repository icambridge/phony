<?php

// TODO look at namespace
namespace Phony\Server\Parser;

interface ParserInterface
{
    /**
     * @param $data
     * @return \Phony\Server\Response
     */
    public function parse($data);
}
