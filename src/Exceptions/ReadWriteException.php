<?php

namespace PagOnline\Exceptions;

/**
 * Class ReadWriteException.
 */
class ReadWriteException extends IOException
{
    public function __construct($url, $message)
    {
        parent::__construct('['.$url.'] '.$message);
    }
}
