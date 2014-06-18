<?php

namespace Phony\Server\Parser;

use Phony\Server\Response;

class Json implements ParserInterface
{
    /**
     * @param $data
     * @return \Phony\Server\Response
     */
    public function parse($data)
    {
        $array = json_decode($data, true);
        $response = new Response($array["method"], $array["uri"], $array["http-code"], $array["content-type"], $array["body"], $array["extra-headers"]);
        return $response;
    }
}
