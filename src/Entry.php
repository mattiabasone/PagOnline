<?php

namespace PagOnline;

use SimpleXMLElement;

class Entry
{
    public $key;
    public $value;

    public function __construct()
    {
    }

    public static function fromXml($xml, $tname)
    {
        if ('' == $xml || null == $xml) {
            return;
        }

        $dom = new SimpleXMLElement($xml, LIBXML_NOERROR, false);
        if (0 == \count($dom)) {
            return;
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
