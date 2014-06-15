<?php

namespace Phony\Server;

class Response
{
    /**
     * The namespace for all the responses to be stored in.
     *
     * @var string
     */
    protected $namespace;

    public function __construct()
    {
        for ($i = 0; $i < 10; $i++) {
            $number = mt_rand(0,9);
            $this->namespace .= (string) $number;
        }
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    public function get($uri)
    {
        $found = false;
        $output = apc_fetch($this->namespace.$uri, $found);

        if (!$found) {
            return [];
        }

        return $output;
    }
}
