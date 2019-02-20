<?php

namespace PagOnline;

use SimpleXMLElement;

/**
 * Class Entry.
 */
class Entry
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $value;

    /**
     * TODO: check why $tname parameter is never used.
     *
     * @param $xml
     * @param $tname
     *
     * @return \PagOnline\Entry|null
     */
    public static function fromXml($xml, $tname): ?self
    {
        if (empty($xml)) {
            return null;
        }

        $dom = new SimpleXMLElement($xml, LIBXML_NOERROR, false);
        if (0 == \count($dom)) {
            return null;
        }

        $response = IgfsUtils::parseResponseFields($dom);
        $entry = null;
        if (isset($response) && \count($response) > 0) {
            $entry = new self();
            $entry->key = (IgfsUtils::getValue($response, 'key'));
            $entry->value = (IgfsUtils::getValue($response, 'value'));
        }

        return $entry;
    }
}
