<?php

namespace PagOnline\XmlEntities;

/**
 * Interface XmlEntityInterface.
 */
interface XmlEntityInterface
{
    /**
     * Export object to XML string.
     *
     * @param string $rootNodeName
     *
     * @return string
     */
    public function toXml(string $rootNodeName): string;

    /**
     * Parse XML and returns XmlEntityInterface.
     *
     * @param string $xml
     *
     * @return null|XmlEntityInterface
     */
    public static function fromXml($xml): ?self;
}
