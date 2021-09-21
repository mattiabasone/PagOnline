<?php

namespace PagOnline\Exceptions;

class ConnectionException extends IOException
{
    /**
     * ConnectionException constructor.
     *
     * @param string $url
     * @param string $message
     */
    public function __construct($url, $message)
    {
        parent::__construct('['.$url.'] '.$message);
    }
}
