<?php

namespace PagOnline;

abstract class BaseIgfsCgRequest
{
    public const CONTENT = '';

    /**
     * @return string
     */
    public function __toString()
    {
        return static::CONTENT;
    }
}
