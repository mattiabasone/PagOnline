<?php

namespace PagOnline\Exceptions;

/**
 * Class ReadWriteException
 * @package PagOnline\Exceptions
 */
class ReadWriteException extends IOException
{
    public function __construct($url, $message)
    {
        parent::__construct('['.$url.'] '.$message);
    }
}
