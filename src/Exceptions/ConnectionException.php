<?php

namespace PagOnline\Exceptions;

/**
 * Class ConnectionException
 * @package PagOnline\Exceptions
 */
class ConnectionException extends IOException
{
    /**
     * ConnectionException constructor.
     * @param $url
     * @param $message
     */
    public function __construct($url, $message)
    {
        parent::__construct('['.$url.'] '.$message);
    }
}
