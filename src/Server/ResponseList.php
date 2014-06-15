<?php

namespace Phony\Server;

class ResponseList
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

    public function shift($uri)
    {
        if (!$this->_exists($uri)) {
            return null;
        }

        $items = $this->_fetch($uri);

        $item = array_shift($items);

        $this->_store($uri, $items);

        return $item;
    }

    public function push($uri, $response)
    {
        $stored = [];
        if ($this->_exists($uri)) {
            $stored = $this->_fetch($uri);
        }
        $stored[] = $response;
        $this->_store($uri, $stored);
    }

    protected function _store($uri, $value, $ttl = 0)
    {
        return apc_store($this->namespace.$uri, $value, $ttl);
    }

    protected function _exists($uri)
    {
        return apc_exists($this->namespace.$uri);
    }

    protected function _fetch($uri)
    {
        return apc_fetch($this->namespace.$uri);
    }
}
