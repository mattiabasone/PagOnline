<?php

namespace PagOnline;

/**
 * Class BaseIgfsCgRequest.
 */
abstract class BaseIgfsCgRequest
{
    const CONTENT = '';

    /**
     * @return string
     */
    public function __toString()
    {
        return static::CONTENT;
    }
}
