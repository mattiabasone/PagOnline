<?php

namespace PagOnline;

interface IgfsCgInterface
{
    /**
     * Get Package version.
     *
     * @return string
     */
    public static function getVersion(): string;

    /**
     * Get request template content.
     *
     * @return string
     */
    public function getRequest(): string;

    /**
     * @return bool
     */
    public function execute(): bool;
}
