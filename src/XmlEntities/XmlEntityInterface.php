<?php

namespace PagOnline\XmlEntities;

/**
 * Interface XmlEntityInterface.
 */
interface XmlEntityInterface
{
    public function toXml(string $rootNodeName): string;
}
