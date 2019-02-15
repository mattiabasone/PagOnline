<?php

namespace PagOnline;

/**
 * Class BaseIgfsCgRequest
 * @package PagOnline
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
